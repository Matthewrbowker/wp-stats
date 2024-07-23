<?php

namespace App\Console\Commands;

use App\Models\Performer;
use App\Models\Year;
use EasyWiki;
use Illuminate\Console\Command;

class ParseArticle extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:parse-article
    {article : The title of the article to parse}

    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parse an article to load into the database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $article = $this->argument( 'article' );
        $api = new EasyWiki( 'https://en.wikipedia.org/w/api.php' );
        $wikitext = $api->getWikitext( $article );

        $isInSection = false;
        $isInTable = false;
        $currentYear = null;
        $currentPerformer = null;
        $rowsAfterBreak = 0;
        $hasYearRow = false;

        $lines = explode( "\n", $wikitext );

        $this->info(print_r($lines, true), "vvv");

        foreach($lines as $line) {
            $this->info("Rows after break: $rowsAfterBreak", "vv");
            $this->info("Line: $line", "vv");
            $this->info("In section: $isInSection", "vv");
            $this->info("In table: $isInTable", "vv");
            if(preg_match( '/===.*===/', $line )) {
                // Start by ignoring the opening text
                $isInSection = true;
            }
            else if(preg_match("/\{\|/", $line)) {
                $isInTable = true;
            }
            else if(preg_match("/\|\}/", $line)) {
                $isInTable = false;
            }
            else if($isInSection && $isInTable) {
                $this->info("In table row: $line", "vv");
                if(str_contains($line, '<ref>')) {
                    // Ignore references
                }
                elseif(str_contains($line, "|-")) {
                    // Reset variables on a row
                    $rowsAfterBreak = 0;
                    $hasYearRow = false;
                }
                else if (str_contains($line, "rowspan")) {
                    preg_match("/(\d{4})/", $line, $matches);
                    $year = $matches[1];
                    $this->info($year, "v");
                    $hasYearRow = true;
                }

                if (($rowsAfterBreak == 2 && !$hasYearRow) || ($rowsAfterBreak == 3 && $hasYearRow)) {
                    $this->info("Performer row", "vv");
                    $performers = explode("{{break}}", $line);
                    $won = false;
                    if(str_contains($line, "{{won")) {
                        $won = true;
                    }
                    foreach($performers as $performer) {
                        $performer = str_replace("{{won|align=left|", "", $performer);
                        $performer = str_replace("}}", "", $performer);
                        $performer = preg_replace("/\<small\>.*\<\/small\>/", "", $performer);
                        $performer = preg_replace("/^\|/", "", $performer);

                        // TODO: Handle links
                        if(str_contains($performer, "[[")) {
                            preg_match("/\[\[(.*)\]\]/", $performer, $matches);
                            if(preg_match("/\|/", $matches[1])) {
                                preg_match("/\|(.*)/", $matches[1], $matches);
                            }
                            $performer = $matches[1];
                        }
                        $performer = trim($performer);
                        $this->info("$performer: " . ($won ? "won": "lost"), "v");
                        if($performer) {
                            $this->info("Inserting performer", "v");
                            $currentPerformer = Performer::firstOrCreate(['name' => $performer]);
                            Year::create([
                                'performer_id' => $currentPerformer->id,
                                'year' => $year,
                                'won' => $won
                            ]);
                        }

                    }
                }
            }

            $rowsAfterBreak++;
        }
    }
}
