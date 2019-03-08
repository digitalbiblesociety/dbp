<?php

use Illuminate\Database\Seeder;

class SeedBibleStrongs extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::connection('dbp')->table('lexicons')->delete();
        $hebrew_lex = \DB::connection('dbp')->table('lexicon_hebrew')->get();
        $greek_lex = \DB::connection('dbp')->table('lexicon_greek')->get();


        foreach ($hebrew_lex as $lex) {
            if(\DB::connection('dbp')->table('lexicons')->where('id','H'.$lex->strongs)->exists()) {
                continue;
            }

            $lex->data = json_decode($lex->data);
            \DB::connection('dbp')->table('lexicons')->insert([
                'id'             => 'H'.$lex->strongs,
                'usage'          => $lex->usage,
                'base_word'      => $lex->base_word,
                'def_lit'        => $lex->data->def->lit ?? null,
                'def_short'      => $lex->data->def->short,
                'def_long'       => collect($lex->data->def->long)->flatten(),
                'deriv'          => $lex->data->deriv ?? null,
                'pronun_ipa'     => $lex->data->pronun->ipa,
                'pronun_ipa_mod' => $lex->data->pronun->ipa_mod,
                'pronun_sbl'     => $lex->data->pronun->sbl,
                'pronun_dic'     => $lex->data->pronun->dic,
                'pronun_dic_mod' => $lex->data->pronun->dic_mod,
                'comment'        => $lex->data->comment ?? null
            ]);
        }

        foreach ($greek_lex as $lex) {
            if(\DB::connection('dbp')->table('lexicons')->where('id','G'.$lex->strongs)->exists()) {
                continue;
            }

            $lex->data = json_decode($lex->data);
            \DB::connection('dbp')->table('lexicons')->insert([
                'id'             => 'G'.$lex->strongs,
                'usage'          => $lex->usage,
                'base_word'      => $lex->base_word,
                'def_lit'        => $lex->data->def->lit ?? null,
                'def_short'      => $lex->data->def->short,
                'def_long'       => collect($lex->data->def->long)->flatten(),
                'deriv'          => $lex->data->deriv ?? null,
                'pronun_ipa'     => $lex->data->pronun->ipa,
                'pronun_ipa_mod' => $lex->data->pronun->ipa_mod,
                'pronun_sbl'     => $lex->data->pronun->sbl,
                'pronun_dic'     => $lex->data->pronun->dic,
                'pronun_dic_mod' => $lex->data->pronun->dic_mod,
                'comment'        => $lex->data->comment ?? null
            ]);
        }

    }
}
