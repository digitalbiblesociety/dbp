<?php

namespace App\Console\Commands\BibleEquivalents;

use App\Models\Organization\Organization;
use Illuminate\Console\Command;

class SyncDigitalBibleLibrary extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dbl:sync {aspect}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //if($command = 'sync') $this->organizations();

        $bible_equivalents = ['b347993c47833204', '38a5ef8e91cac158', 'db2b010bc71cbcef', 'cb09d08f864718a9', 'f42fc4b21583dad3', '41acde325876798a', '16c2ae6602e699ce', 'e4be884e82a429bb', 'debeca57efd19bc2', '36a0f78a6b3da8ba', '5c82a5b4ccb94e76', 'b7112053bc602357', 'f5e9ffc5f2faa91e', '4bdf27db603e8a08', '13767247c4090f1c', 'c552ccb9e7d40ce6', '259a3306d9564cb6', 'c634fab20bd96112', 'dca772a6b8227d8a', 'f162be7554f3b482', '5f6adf67ae80e262', 'c3d43c21bad01a82', 'e3cb28f08102e0f3', '9e30a03c5a46dce1', 'fa7bb1485e7a9fa7', '83d17ee935523638', '2ed679542fab921d', '3a719eeee82838e7', '799115dd9f12e8f7', '2e613b400fb31965', 'aa7260841cc1e8cf', 'd4ee8bd515e066de', '9f78f34aabe691c9', 'e82e3f94be6eb19d', '95d17eb7ab81fcf4', 'a37e1df3b5b13da8', 'c22e838e3a3301e7', '2647669d5b9c3f63', '8f4edbe5cc0aa001', '253ca6a6dfb917ab', '8d719a6999f69101', '50342b39556c637b', '4605369d47fcf630', 'b64fdaaef84f0bc9', '37e4e88535e79c41', '987502e4d74c76aa', '621b2076a4942f05', '1bcc0df55e93bd63', 'eb767898b32fd96d', 'c268ac24bfea9055', 'db427f4fbf3d0ffc', 'd2bcf1334956bcc3', 'fd5611d2910878bc', 'aee9474b4a88eefb', '8c94dc30784fedec', 'd00192a2414f7567', 'ac205cc87ccadfd6', '375e601af3b16eb8', '972e947e0667b722', '340662ffaaab18a0', '3394ed13ade34fbc', 'cc617ebfd1dd6224', 'f7a29bc708c1cec1', '745b1e89928f3f37', '34988bf0c03c0432', 'd671096143a78cc1', '524b0f0a4457738d', '04c8a4b759e9b41c', '6a047085889d3637', '59b9fa377b341097', '19b20027201977c5', '66009f8c774182d0', '8676091b758f0d8d', 'be732654d8fe41bd', 'ae97ee4b7199e15a', '4f684f4a0bee8478', '221f66cab1c6f8a1', 'bae8cba68f2f143f', '78750c5e553d77d2', '361230230363baec', '1c1f3e283357fbe6', '8871df6dba8ebcdd', 'e411905ce99fcb59', '68832f724b923368', 'aeac580f739e841c', '96ce7ba424637835', '0c4735416f70e072', '5542a8cd0429163d', 'a3854c96fd2203d2', '2f416c6f03dd8090', '26b7f37588f4c5ce', '9e63cdeff066ea65', '4312d7ee99fdec46', '91f8949f7dcf0585', 'c7eb32ae41dea051', '16de09806afe779f', 'bc2c2a9b54e31c9f', '9916bfaeeb62faac', '99d1420a104be966', '22d8644a651bf3b2', '00911a07694470fa', '0b30f7a64e7e17b0', 'e50e1eac1cd1073b', '08a4086851bfb1ed', 'ef16f515a091f55b', 'cd23cb08c39ff641', 'f1b4d233684ac6b7', 'd6f4cdb154fea079', 'a558658968d8d302', '043f34213156ec4b', '55bf7d46632bb31e', '24304b250810722c', 'fa72cee2a6497eb8', '6448f5b0238b9c6c', '64e75ededf9406b6', '3e08146aeb79d7ea', '1fb91d2e0a5f51f5', '897f91fb2077c0b1', 'ee142c62e2932398', 'a0eead58359eebd2', 'd06f97d18e3c0e0f', '15d7b7813bfa2801', '81df4250bbf1c9be', 'ffca4526975425ae', 'bac16c8b618be81f', '22369d764b3d60a2', '95617ff51f03b6b8', '0b94c3c6951be8ba', '231f14f3162b54c9', '546e77c26f034ec6', '9b1c00cd27707b24', 'dac7e6d690608d3e', '300672556a449b25', 'e24b405291c6f45f', '4a203667d02c23d7', '3fa4c85c95be3aa6', '37740b8b57757368', '095bf21a0fbc1477', 'b7bcc8d842e33502', '0c58aad1794dc647', 'fa052d4be2f94ac4', 'bd0b326ffd9b3761', '112be6b25474c13d', 'f805c3334f0d9b3d', 'c44a6a7429b68f0f', 'd980ad19668bb50d', 'b4c47db8fcdecdd3', '9cf034e8ab176724', '9e7600f5f3e383d8', '6bc3720cefe5ec26', '882c692e5a213be7', 'da8cbfc080f48818', 'f370b3da13dad173', 'd237b75b1095af18', '9e1088d1d95f3aa9', '5606d5e01cb7fec1', '28d9ddbd6f0244cd', '7c027c9eab018d02', '3410eec89a3ed19f', '26e0bbc90cf70432', '597b488573c7f741', '12bb74b60088b209', 'f6b5c4f8273c1aee', '7140e4a22e4b557b', 'df7d5d71526afe9e', '7f74c4136b703784', '66283bab67192fa1', '812979be5cea477f', '7b929cf7aea665a3', '19cd186174de646a', '5dccbb8496c67080', 'c9d4748bb8d2cd9a', '4df5178e9f46c3fb', '2b00df52326f6705', '2eccf1796085d174', '22b6f0cee3f69bfd', '38abbbe1e11d172f', '343ff7def331c54b', 'e4a6c48f010eec59', 'bfe3d27ca02d8188', 'fa5c2f1ce7fa9b82', '1a340f5ae072b2f4', '5e218a0fc5db6875', 'c058c88d8250bfb0', 'dbb06dc7ed7788c4', '4b646272c79c5d32', 'a556c5305ee15c3f', 'fc0cc6db0df2e410', '7dbdb4d39d4a8d83', 'c5a761cecc57ab5c', '2b1625d1f4236ce5', '95b764900c64c6d2', 'f1358a170d76f24f', 'a91fc6613e36390f', '7e094294de93fdbc', 'f425394cc4a3cd5a', 'cf904fc22ebb8015', 'aeeff77cf7e743c9', '61cb9ab58510baff', 'b329b7bf9e78632c', '7a29e1242c9ee60d', '58e62cbf25313022', '597a4b6f7639ff59', '937fe19add254cf3', '3548ab6114a312d4', 'c95a198da16f1681', 'ce6497fe43bb7d3d', 'a4ec810a0b58a2eb', '875766e910657889', '187adfee4c8f868e', '07d8ac82da5d525e', 'd7f7cae664680674', '0c8e333a7aa78c92', 'cb5dbaee527dccf3', 'eb779d9d3dc4fd6e', 'e10c865a9b4054ae', 'b0e70bfbabbabbd6', '2fa49e4da80d0e38', '6ca787a577ebfb8a', '6788d443de22f979', 'd9e6de2a87b4e8ac', 'd2f50421716b5a3e', 'c92287fff282de60', 'd646b47beb54e69c', 'ca94fec9e35bb807', 'c23356f01aedfe61', '930aab03c96d72aa', '07ed9ca493c59234', '27d398e76e8b43bf', '7b2acc127ab19d99', 'd219732427fc797d', 'f2d1225ca6b872c6', '0acfa9d0e474b43a', '73ecd15376e7db01', '4da6c78a5daa566c', '17c44f6c89de00db', 'b672e26d4f7835ae', '94ac71cadd884254', 'a5351e03c0944b14', '7a05a66c170e79f9', '365f988242c307d2', 'cd05a7dd5f61364e', 'ebcffc72103e5c5f', 'e64fe1c266153ee3', '9e9143e14787b913', '4de26e18fe3e46cc', '964c74dd217305b7', 'da84ad82c8887d25', '56b8e6e6b02c5e89', 'f206c1e04f866b3e', '6fe82008a663e60e', '55c4cb758a08f8a4', '388e07e45913a9ba', '7a5d4e27fec3509e', '52fd6d389373d675', '5feee0321ad67d9e', '246fadbadcb1e783', 'b52bc8b7af3bdc6f', 'a8931093e4e3baf3', '8f7d32e3766e73b4', '8be97fa5c75c4f72', '259b581901040f49', '18d745e496b93ea7', '0f7c90b391767b95', '2eb4c956c00a6b24', '55461eb84986c387', '6a795af03e157c90', 'f0b18ec9dfec16fd', '4338bcf7a310c65f', 'c9332f97a27c1f6b', '9e944fc08f02acf9', '86195bc24616fc20', 'cbf3135b65599956', '6ca982d68850ea22', 'a84d0ff2d9846edf', '4ec29e6125f12df1', '135ccad98d972c41', 'f0d54c2e201947c5', '6eda79520b919447', '4ffcec8e8f3c33e7', '0e63d3c99a03b526', 'ef88d0e04f349974', '230818817fa2d7ac', '0848b63d9e2d1d53', '5f2db45b5778cb82', '5588c01125b7d194', '07096fbda2bbd5cf', 'f8f54202645f6861', '0393612187b654b8', '6a5b8388fac4dda9', 'ce3c03e514e5cd89', 'fdb388b5e5e271be', 'f357bc5e434d1675', '8b19abf5db2592ac', 'fbb56b9b74e17435', '1279c83bcdc94118', 'df7e97ad10444eb0', 'f9af105ac9dfff5d', '66992eaaf3106fcf', '1a8c113f6692a8a3', '12b91ec711c44b0e', 'b9bb82b63632cfd6', '899087b6b2dac460', '5ad758fc90f8b6b4', 'f75ec9abac4d7747', '9712485bf806c636', '53b04f4df1f82934', '75161331411e33e8', '365df148f7d55c34', '4e0306ced143b0c0'];
        foreach ($bible_equivalents as $bible_equivalent) {
            //file_get_contents('https://thedigitalbiblelibrary.org/api')
        }
    }

    private function organizations()
    {
        // Fetch Local Organizations
        $organizations = Organization::with('relationships')->get();
        $dbl = $organizations->where('slug', 'digital-bible-library')->first();

        // Fetch Organizations from the DBL
        $dbl_organization_entries = cacheRemember('dbl_organizations', [], now()->addMinutes(30), function () {
            return json_decode(file_get_contents('https://thedigitalbiblelibrary.org/api/'));
        });

        // Sync them
        foreach ($dbl_organization_entries as $dbl_organization_entry) {
        }
    }
}
