<?php

use Illuminate\Database\Seeder;
use App\Models\Bible\BibleEquivalent;

class bible_equivalents_gbc extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $GBC = glob(storage_path().'/data/GBC/*.json');
        foreach($GBC as $bible) {
            $bible = json_decode(file_get_contents($bible), true);
            if($bible['in_dbl']) {
                foreach($bible['identification_systemId'] as $system) {
                    if($system['type'] == 'dbl') {
                        $dbl_equivalent = BibleEquivalent::find($system['text']);
                        if($dbl_equivalent) {
                            $equivalent = new BibleEquivalent();
                            $equivalent->create(['abbr' => $dbl_equivalent->abbr, 'equivalent_id' => $bible['id'], 'type' => "archive", 'partner' => 'gbc']);
                        }
                    }
                }
            }
        }

    }
}





/*

{
    "agencies_creator":"Wycliffe Bible Translators, Inc.",
   "type_translationType":"New",
   "in_cam":false,
   "language_speakers":19400,
   "language_script":"Latin",
   "language_scriptCode":"Latn",
   "id":"53ac243a5117ad56de235a4a",
   "in_dbl":true,
   "in_tms":false,
   "copyright":"\u00a9 2004, Wycliffe Bible Translators, Inc. All rights reserved.",
   "area":"Pacific",
   "identification_systemId":[
      {
          "text":"1d195f0748db203a",
         "type":"dbl"
      },
      {
          "text":"1d195f0748db203ae77f8508a505fe28030d8241",
         "fullname":"Kalo (Keapara) NT [khz] -Papua New Guinea 2005 (DBL 2014)",
         "type":"paratext",
         "name":"khzDBL"
      },
      {
          "text":"http://www.reap.insitehome.org/handle/9284745/21159",
         "type":"reap"
      }
   ],
   "identification_dateCompleted":"2004",
   "language_iso":"khz",
   "agencies_etenPartner":"WBT",
   "country_iso":"PG",
   "country_name":"Papua New Guinea",
   "language_name":"Keapara",
   "type_audience":"Common",
   "status":"Published",
   "confidential":false,
   "editable":false,
   "agencies_publisher":"Wycliffe Bible Translators, Inc.",
   "identification_scope":"New Testament",
   "agencies_contributors":[
      {
          "in_dbl":true,
         "publication":true,
         "url":"http://www.wycliffe.org",
         "nameLocal":"Wycliffe Bible Translators, Inc.",
         "content":true,
         "abbr":"WBT",
         "id":"545d2cb00be06579ca809b57",
         "name":"Wycliffe Bible Translators, Inc."
      },
      {
          "in_dbl":true,
         "name":"Bible Society of Papua New Guinea",
         "url":"",
         "id":"54650ce35117ad68c4c49e87",
         "abbr":"PNGBS",
         "nameLocal":""
      }
   ],
   "identification_nameLocal":"Nupela Testamen long tokples Kalo long Niugini",
   "identification_abbreviationLocal":"khz",
   "agencies_rightsHolders":[
      {
          "in_dbl":true,
         "name":"Wycliffe Bible Translators, Inc.",
         "url":"http://www.wycliffe.org",
         "nameLocal":"Wycliffe Bible Translators, Inc.",
         "facebook":"http://www.facebook.com/WycliffeUSA",
         "abbr":"WBT",
         "id":"545d2cb00be06579ca809b57"
      }
   ],
   "identification_description":"New Testament in Keapara",
   "in_bmt":false,
   "identification_name":"The New Testament in the Kalo",
   "bookNames":[
      {
          "code":"MAT",
         "abbr":"Mat",
         "long":"Vali Namana Mataio na etaloato",
         "short":"Mataio"
      },
      {
          "code":"MRK",
         "abbr":"Mak",
         "long":"Vali Namana Mareko na etaloato",
         "short":"Mareko"
      },
      {
          "code":"LUK",
         "abbr":"Luk",
         "long":"Vali Namana Luka na etaloato",
         "short":"Luka"
      },
      {
          "code":"JHN",
         "abbr":"Ioa",
         "long":"Vali Namana Ioane na etaloato",
         "short":"Ioane"
      },
      {
          "code":"ACT",
         "abbr":"Apo",
         "long":"Apostolo geria kala e inagulo pa Apostolo",
         "short":"Apostolo"
      },
      {
          "code":"ROM",
         "abbr":"Rom",
         "long":"Paul gena talotalo Roma ekalesiara geria",
         "short":"Roma"
      },
      {
          "code":"1CO",
         "abbr":"1Kor",
         "long":"Paul gena talotalo tovotovona Korinto ekalesiara geria",
         "short":"1 Korinto"
      },
      {
          "code":"2CO",
         "abbr":"2Kor",
         "long":"Paul gena talotalo vega-rualana Korinto ekalesiara geria",
         "short":"2 Korinto"
      },
      {
          "code":"GAL",
         "abbr":"Gal",
         "long":"Paul gena talotalo Galatia ekalesiara geria",
         "short":"Galatia"
      },
      {
          "code":"EPH",
         "abbr":"Efe",
         "long":"Paul gena talotalo Epeso ekalesiara geria",
         "short":"Efeso"
      },
      {
          "code":"PHP",
         "abbr":"Fil",
         "long":"Paul gena talotalo Filipi ekalesiara geria",
         "short":"Filipi"
      },
      {
          "code":"COL",
         "abbr":"Kol",
         "long":"Paul gena talotalo Kolose ekalesiara geria",
         "short":"Kolose"
      },
      {
          "code":"1TH",
         "abbr":"1Tes",
         "long":"Paul gena talotalo tovotovona Tesalonika ekalesiara geria",
         "short":"1 Tesalonika"
      },
      {
          "code":"2TH",
         "abbr":"2Tes",
         "long":"Paul gena talotalo vega-rualana Tesalonika ekalesiara geria",
         "short":"2 Tesalonika"
      },
      {
          "code":"1TI",
         "abbr":"1Tim",
         "long":"Paul gena talotalo tovotovona Timoteo gena",
         "short":"1 Timoteo"
      },
      {
          "code":"2TI",
         "abbr":"2Tim",
         "long":"Paul gena talotalo vega-rualana Timoteo gena",
         "short":"2 Timoteo"
      },
      {
          "code":"TIT",
         "abbr":"Tit",
         "long":"Paul gena talotalo Tito gena",
         "short":"tito"
      },
      {
          "code":"PHM",
         "abbr":"Flm",
         "long":"Paul gena talotalo Filemona gena",
         "short":"Filemona"
      },
      {
          "code":"HEB",
         "abbr":"Heb",
         "long":"Heberu",
         "short":"Heberu"
      },
      {
          "code":"JAS",
         "abbr":"Tei",
         "long":"Teimiti gena talotalo",
         "short":"Teimiti"
      },
      {
          "code":"1PE",
         "abbr":"1Pet",
         "long":"Petero gena talotalo tovotovona",
         "short":"1 Petero"
      },
      {
          "code":"2PE",
         "abbr":"2Pet",
         "long":"Petero gena talotalo vega-rualana",
         "short":"2 Petero"
      },
      {
          "code":"1JN",
         "abbr":"1Ioa",
         "long":"Ioane gena talotalo tovotovona",
         "short":"1 Ioane"
      },
      {
          "code":"2JN",
         "abbr":"2Ioa",
         "long":"Ioane gena talotalo vega-rualana",
         "short":"2 Ioane"
      },
      {
          "code":"3JN",
         "abbr":"3Ioa",
         "long":"Ioane gena talotalo vega-toitoina",
         "short":"3 Ioane"
      },
      {
          "code":"JUD",
         "abbr":"Tiu",
         "long":"Tiudi gena talotalo",
         "short":"Tiudi"
      },
      {
          "code":"REV",
         "abbr":"Vev",
         "long":"Vevega-matagai",
         "short":"Vevega-matagai"
      }
   ],
   "identification_abbreviation":"khz"
}

*/