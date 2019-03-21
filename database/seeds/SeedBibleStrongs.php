<?php

use Illuminate\Database\Seeder;

use \App\Models\Bible\Study\Lexicon;
use \App\Models\Bible\Study\LexicalDefinition;
use \App\Models\Bible\Study\LexicalPronunciation;
class SeedBibleStrongs extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::connection('dbp')->table('lexical_pronunciations')->delete();
        \DB::connection('dbp')->table('lexical_definitions')->delete();
        \DB::connection('dbp')->table('lexicons')->delete();

        $this->seedLexicons('greek', 'G');
        $this->seedLexicons('hebrew','H');
    }

    private function seedLexicons($language, $letter)
    {
        $lexicons = \DB::connection('dbp')->table('lexicon_'.$language)->get();
        foreach ($lexicons as $lex) {
            if(Lexicon::where('id', $letter.$lex->strongs)->exists()) {
                continue;
            }

            $lex->data = json_decode($lex->data);
            $this->seedLexicalDefinitions($lex, $letter);
        }
    }

    private function seedLexicalDefinitions($lex, $word_letter)
    {
        $known_keys = collect(['def', 'deriv', 'pronun', 'see', 'comment', 'aramaic', 'id', 'strongs', 'base_word', 'data', 'usage', 'part_of_speech']);

        if(!collect($lex)->keys()->diff($known_keys)->isEmpty()) {
            dd(collect($lex)->keys()->diff($known_keys));
        }

        Lexicon::insert([
            'id'                    => $word_letter.$lex->strongs,
            'usage'                 => $lex->usage,
            'base_word'             => $lex->base_word,
            'part_of_speech'        => $lex->part_of_speech ?? null,
            'definition'            => $lex->data->def->short,
            'derived'               => $lex->data->deriv ?? null,
            'aramaic'               => $lex->data->aramaic ?? null,
            'comment'               => $lex->data->comment ?? null
        ]);

        LexicalPronunciation::insert([
            'lexicon_id'   => $word_letter.$lex->strongs,
            'ipa'          => $lex->data->pronun->ipa,
            'ipa_mod'      => $lex->data->pronun->ipa_mod,
            'sbl'          => $lex->data->pronun->sbl,
            'dic'          => $lex->data->pronun->dic,
            'dic_mod'      => $lex->data->pronun->dic_mod,
        ]);

        if(isset($lex->data->def->lit)) {
            LexicalDefinition::create([
                'lexicon_id'   => $word_letter.$lex->strongs,
                'literal'      => true,
                'definition'   => $lex->data->def->lit
            ]);
        }

        $to_skip = null;
        foreach (collect($lex->data->def->long) as $definition) {
            if(is_array($definition)) {
                foreach($definition as $key => $word) {

                    if($to_skip === $key) {
                        $to_skip = null;
                        continue;
                    }

                    // Some nested definitions
                    $next = @$definition[$key + 1];
                    if(is_array($next)) {

                        foreach ($next as $sub_word) {
                            if(is_array($sub_word)) {
                                if(count($sub_word) === 1) {
                                    $sub_word = $sub_word[0];
                                } else {
                                    $sub_word = collect($sub_word)->flatten();
                                }
                            }

                            LexicalDefinition::create([
                                'lexicon_id'   => $word_letter.$lex->strongs,
                                'word_variant' => $word,
                                'definition'   => $sub_word
                            ]);
                        }
                        $to_skip = $key + 1;

                    } else {
                        LexicalDefinition::create([
                            'lexicon_id' => $word_letter.$lex->strongs,
                            'definition' => $word
                        ]);
                    }


                }
            } else {
                LexicalDefinition::create([
                    'lexicon_id' => $word_letter.$lex->strongs,
                    'definition' => $definition
                ]);
            }
        }
    }


}
