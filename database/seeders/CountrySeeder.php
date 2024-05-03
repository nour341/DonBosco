<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {


        $countries = [
            [
                'id' => 1,
                'code' => 'AF',
                'name' => 'Afghanistan',
                'phoneCode' => 93
            ],
            [
                'id' => 2,
                'code' => 'AL',
                'name' => 'Albania',
                'phoneCode' => 355
            ],
            [
                'id' => 3,
                'code' => 'DZ',
                'name' => 'Algeria',
                'phoneCode' => 213
            ],
            [
                'id' => 4,
                'code' => 'AS',
                'name' => 'American Samoa',
                'phoneCode' => 1684
            ],
            [
                'id' => 5,
                'code' => 'AD',
                'name' => 'Andorra',
                'phoneCode' => 376
            ],
            [
                'id' => 6,
                'code' => 'AO',
                'name' => 'Angola',
                'phoneCode' => 244
            ],
            [
                'id' => 7,
                'code' => 'AI',
                'name' => 'Anguilla',
                'phoneCode' => 1264
            ],
            [
                'id' => 8,
                'code' => 'AQ',
                'name' => 'Antarctica',
                'phoneCode' => 0
            ],
            [
                'id' => 9,
                'code' => 'AG',
                'name' => 'Antigua And Barbuda',
                'phoneCode' => 1268
            ],
            [
                'id' => 10,
                'code' => 'AR',
                'name' => 'Argentina',
                'phoneCode' => 54
            ],
            [
                'id' => 11,
                'code' => 'AM',
                'name' => 'Armenia',
                'phoneCode' => 374
            ],
            [
                'id' => 12,
                'code' => 'AW',
                'name' => 'Aruba',
                'phoneCode' => 297
            ],
            [
                'id' => 13,
                'code' => 'AU',
                'name' => 'Australia',
                'phoneCode' => 61
            ],
            [
                'id' => 14,
                'code' => 'AT',
                'name' => 'Austria',
                'phoneCode' => 43
            ],
            [
                'id' => 15,
                'code' => 'AZ',
                'name' => 'Azerbaijan',
                'phoneCode' => 994
            ],
            [
                'id' => 16,
                'code' => 'BS',
                'name' => 'Bahamas The',
                'phoneCode' => 1242
            ],
            [
                'id' => 17,
                'code' => 'BH',
                'name' => 'Bahrain',
                'phoneCode' => 973
            ],
            [
                'id' => 18,
                'code' => 'BD',
                'name' => 'Bangladesh',
                'phoneCode' => 880
            ],
            [
                'id' => 19,
                'code' => 'BB',
                'name' => 'Barbados',
                'phoneCode' => 1246
            ],
            [
                'id' => 20,
                'code' => 'BY',
                'name' => 'Belarus',
                'phoneCode' => 375
            ],
            [
                'id' => 21,
                'code' => 'BE',
                'name' => 'Belgium',
                'phoneCode' => 32
            ],
            [
                'id' => 22,
                'code' => 'BZ',
                'name' => 'Belize',
                'phoneCode' => 501
            ],
            [
                'id' => 23,
                'code' => 'BJ',
                'name' => 'Benin',
                'phoneCode' => 229
            ],
            [
                'id' => 24,
                'code' => 'BM',
                'name' => 'Bermuda',
                'phoneCode' => 1441
            ],
            [
                'id' => 25,
                'code' => 'BT',
                'name' => 'Bhutan',
                'phoneCode' => 975
            ],
            [
                'id' => 26,
                'code' => 'BO',
                'name' => 'Bolivia',
                'phoneCode' => 591
            ],
            [
                'id' => 27,
                'code' => 'BA',
                'name' => 'Bosnia and Herzegovina',
                'phoneCode' => 387
            ],
            [
                'id' => 28,
                'code' => 'BW',
                'name' => 'Botswana',
                'phoneCode' => 267
            ],
            [
                'id' => 29,
                'code' => 'BV',
                'name' => 'Bouvet Island',
                'phoneCode' => 0
            ],
            [
                'id' => 30,
                'code' => 'BR',
                'name' => 'Brazil',
                'phoneCode' => 55
            ],
            [
                'id' => 31,
                'code' => 'IO',
                'name' => 'British Indian Ocean Territory',
                'phoneCode' => 246
            ],
            [
                'id' => 32,
                'code' => 'BN',
                'name' => 'Brunei',
                'phoneCode' => 673
            ],
            [
                'id' => 33,
                'code' => 'BG',
                'name' => 'Bulgaria',
                'phoneCode' => 359
            ],
            [
                'id' => 34,
                'code' => 'BF',
                'name' => 'Burkina Faso',
                'phoneCode' => 226
            ],
            [
                'id' => 35,
                'code' => 'BI',
                'name' => 'Burundi',
                'phoneCode' => 257
            ],
            [
                'id' => 36,
                'code' => 'KH',
                'name' => 'Cambodia',
                'phoneCode' => 855
            ],
            [
                'id' => 37,
                'code' => 'CM',
                'name' => 'Cameroon',
                'phoneCode' => 237
            ],
            [
                'id' => 38,
                'code' => 'CA',
                'name' => 'Canada',
                'phoneCode' => 1
            ],
            [
                'id' => 39,
                'code' => 'CV',
                'name' => 'Cape Verde',
                'phoneCode' => 238
            ],
            [
                'id' => 40,
                'code' => 'KY',
                'name' => 'Cayman Islands',
                'phoneCode' => 1345
            ],
            [
                'id' => 41,
                'code' => 'CF',
                'name' => 'Central African Republic',
                'phoneCode' => 236
            ],
            [
                'id' => 42,
                'code' => 'TD',
                'name' => 'Chad',
                'phoneCode' => 235
            ],
            [
                'id' => 43,
                'code' => 'CL',
                'name' => 'Chile',
                'phoneCode' => 56
            ],
            [
                'id' => 44,
                'code' => 'CN',
                'name' => 'China',
                'phoneCode' => 86
            ],
            [
                'id' => 45,
                'code' => 'CX',
                'name' => 'Christmas Island',
                'phoneCode' => 61
            ],
            [
                'id' => 46,
                'code' => 'CC',
                'name' => 'Cocos (Keeling) Islands',
                'phoneCode' => 672
            ],
            [
                'id' => 47,
                'code' => 'CO',
                'name' => 'Colombia',
                'phoneCode' => 57
            ],
            [
                'id' => 48,
                'code' => 'KM',
                'name' => 'Comoros',
                'phoneCode' => 269
            ],
            [
                'id' => 49,
                'code' => 'CG',
                'name' => 'Republic Of The Congo',
                'phoneCode' => 242
            ],
            [
                'id' => 50,
                'code' => 'CD',
                'name' => 'Democratic Republic Of The Congo',
                'phoneCode' => 242
            ],
            [
                'id' => 51,
                'code' => 'CK',
                'name' => 'Cook Islands',
                'phoneCode' => 682
            ],
            [
                'id' => 52,
                'code' => 'CR',
                'name' => 'Costa Rica',
                'phoneCode' => 506
            ],
            [
                'id' => 53,
                'code' => 'CI',
                'name' => 'Cote D Ivoire (Ivory Coast)',
                'phoneCode' => 225
            ],
            [
                'id' => 54,
                'code' => 'HR',
                'name' => 'Croatia (Hrvatska)',
                'phoneCode' => 385
            ],
            [
                'id' => 55,
                'code' => 'CU',
                'name' => 'Cuba',
                'phoneCode' => 53
            ],
            [
                'id' => 56,
                'code' => 'CY',
                'name' => 'Cyprus',
                'phoneCode' => 357
            ],
            [
                'id' => 57,
                'code' => 'CZ',
                'name' => 'Czech Republic',
                'phoneCode' => 420
            ],
            [
                'id' => 58,
                'code' => 'DK',
                'name' => 'Denmark',
                'phoneCode' => 45
            ],
            [
                'id' => 59,
                'code' => 'DJ',
                'name' => 'Djibouti',
                'phoneCode' => 253
            ],
            [
                'id' => 60,
                'code' => 'DM',
                'name' => 'Dominica',
                'phoneCode' => 1767
            ],
            [
                'id' => 61,
                'code' => 'DO',
                'name' => 'Dominican Republic',
                'phoneCode' => 1809
            ],
            [
                'id' => 62,
                'code' => 'TP',
                'name' => 'East Timor',
                'phoneCode' => 670
            ],
            [
                'id' => 63,
                'code' => 'EC',
                'name' => 'Ecuador',
                'phoneCode' => 593
            ],
            [
                'id' => 64,
                'code' => 'EG',
                'name' => 'Egypt',
                'phoneCode' => 20
            ],
            [
                'id' => 65,
                'code' => 'SV',
                'name' => 'El Salvador',
                'phoneCode' => 503
            ],
            [
                'id' => 66,
                'code' => 'GQ',
                'name' => 'Equatorial Guinea',
                'phoneCode' => 240
            ],
            [
                'id' => 67,
                'code' => 'ER',
                'name' => 'Eritrea',
                'phoneCode' => 291
            ],
            [
                'id' => 68,
                'code' => 'EE',
                'name' => 'Estonia',
                'phoneCode' => 372
            ],
            [
                'id' => 69,
                'code' => 'ET',
                'name' => 'Ethiopia',
                'phoneCode' => 251
            ],
            [
                'id' => 70,
                'code' => 'XA',
                'name' => 'External Territories of Australia',
                'phoneCode' => 61
            ],
            [
                'id' => 71,
                'code' => 'FK',
                'name' => 'Falkland Islands',
                'phoneCode' => 500
            ],
            [
                'id' => 72,
                'code' => 'FO',
                'name' => 'Faroe Islands',
                'phoneCode' => 298
            ],
            [
                'id' => 73,
                'code' => 'FJ',
                'name' => 'Fiji Islands',
                'phoneCode' => 679
            ],
            [
                'id' => 74,
                'code' => 'FI',
                'name' => 'Finland',
                'phoneCode' => 358
            ],
            [
                'id' => 75,
                'code' => 'FR',
                'name' => 'France',
                'phoneCode' => 33
            ],
            [
                'id' => 76,
                'code' => 'GF',
                'name' => 'French Guiana',
                'phoneCode' => 594
            ],
            [
                'id' => 77,
                'code' => 'PF',
                'name' => 'French Polynesia',
                'phoneCode' => 689
            ],
            [
                'id' => 78,
                'code' => 'TF',
                'name' => 'French Southern Territories',
                'phoneCode' => 0
            ],
            [
                'id' => 79,
                'code' => 'GA',
                'name' => 'Gabon',
                'phoneCode' => 241
            ],
            [
                'id' => 80,
                'code' => 'GM',
                'name' => 'Gambia The',
                'phoneCode' => 220
            ],
            [
                'id' => 81,
                'code' => 'GE',
                'name' => 'Georgia',
                'phoneCode' => 995
            ],
            [
                'id' => 82,
                'code' => 'DE',
                'name' => 'Germany',
                'phoneCode' => 49
            ],
            [
                'id' => 83,
                'code' => 'GH',
                'name' => 'Ghana',
                'phoneCode' => 233
            ],
            [
                'id' => 84,
                'code' => 'GI',
                'name' => 'Gibraltar',
                'phoneCode' => 350
            ],
            [
                'id' => 85,
                'code' => 'GR',
                'name' => 'Greece',
                'phoneCode' => 30
            ],
            [
                'id' => 86,
                'code' => 'GL',
                'name' => 'Greenland',
                'phoneCode' => 299
            ],
            [
                'id' => 87,
                'code' => 'GD',
                'name' => 'Grenada',
                'phoneCode' => 1473
            ],
            [
                'id' => 88,
                'code' => 'GP',
                'name' => 'Guadeloupe',
                'phoneCode' => 590
            ],
            [
                'id' => 89,
                'code' => 'GU',
                'name' => 'Guam',
                'phoneCode' => 1671
            ],
            [
                'id' => 90,
                'code' => 'GT',
                'name' => 'Guatemala',
                'phoneCode' => 502
            ],
            [
                'id' => 91,
                'code' => 'XU',
                'name' => 'Guernsey and Alderney',
                'phoneCode' => 44
            ],
            [
                'id' => 92,
                'code' => 'GN',
                'name' => 'Guinea',
                'phoneCode' => 224
            ],
            [
                'id' => 93,
                'code' => 'GW',
                'name' => 'Guinea-Bissau',
                'phoneCode' => 245
            ],
            [
                'id' => 94,
                'code' => 'GY',
                'name' => 'Guyana',
                'phoneCode' => 592
            ],
            [
                'id' => 95,
                'code' => 'HT',
                'name' => 'Haiti',
                'phoneCode' => 509
            ],
            [
                'id' => 96,
                'code' => 'HM',
                'name' => 'Heard and McDonald Islands',
                'phoneCode' => 0
            ],
            [
                'id' => 97,
                'code' => 'HN',
                'name' => 'Honduras',
                'phoneCode' => 504
            ],
            [
                'id' => 98,
                'code' => 'HK',
                'name' => 'Hong Kong S.A.R.',
                'phoneCode' => 852
            ],
            [
                'id' => 99,
                'code' => 'HU',
                'name' => 'Hungary',
                'phoneCode' => 36
            ],
            [
                'id' => 100,
                'code' => 'IS',
                'name' => 'Iceland',
                'phoneCode' => 354
            ],
            [
                'id' => 101,
                'code' => 'IN',
                'name' => 'India',
                'phoneCode' => 91
            ],
            [
                'id' => 102,
                'code' => 'id',
                'name' => 'Indonesia',
                'phoneCode' => 62
            ],
            [
                'id' => 103,
                'code' => 'IR',
                'name' => 'Iran',
                'phoneCode' => 98
            ],
            [
                'id' => 104,
                'code' => 'IQ',
                'name' => 'Iraq',
                'phoneCode' => 964
            ],
            [
                'id' => 105,
                'code' => 'IE',
                'name' => 'Ireland',
                'phoneCode' => 353
            ],
            [
                'id' => 106,
                'code' => 'IL',
                'name' => 'Israel',
                'phoneCode' => 972
            ],
            [
                'id' => 107,
                'code' => 'IT',
                'name' => 'Italy',
                'phoneCode' => 39
            ],
            [
                'id' => 108,
                'code' => 'JM',
                'name' => 'Jamaica',
                'phoneCode' => 1876
            ],
            [
                'id' => 109,
                'code' => 'JP',
                'name' => 'Japan',
                'phoneCode' => 81
            ],
            [
                'id' => 110,
                'code' => 'XJ',
                'name' => 'Jersey',
                'phoneCode' => 44
            ],
            [
                'id' => 111,
                'code' => 'JO',
                'name' => 'Jordan',
                'phoneCode' => 962
            ],
            [
                'id' => 112,
                'code' => 'KZ',
                'name' => 'Kazakhstan',
                'phoneCode' => 7
            ],
            [
                'id' => 113,
                'code' => 'KE',
                'name' => 'Kenya',
                'phoneCode' => 254
            ],
            [
                'id' => 114,
                'code' => 'KI',
                'name' => 'Kiribati',
                'phoneCode' => 686
            ],
            [
                'id' => 115,
                'code' => 'KP',
                'name' => 'Korea North',
                'phoneCode' => 850
            ],
            [
                'id' => 116,
                'code' => 'KR',
                'name' => 'Korea South',
                'phoneCode' => 82
            ],
            [
                'id' => 117,
                'code' => 'KW',
                'name' => 'Kuwait',
                'phoneCode' => 965
            ],
            [
                'id' => 118,
                'code' => 'KG',
                'name' => 'Kyrgyzstan',
                'phoneCode' => 996
            ],
            [
                'id' => 119,
                'code' => 'LA',
                'name' => 'Laos',
                'phoneCode' => 856
            ],
            [
                'id' => 120,
                'code' => 'LV',
                'name' => 'Latvia',
                'phoneCode' => 371
            ],
            [
                'id' => 121,
                'code' => 'LB',
                'name' => 'Lebanon',
                'phoneCode' => 961
            ],
            [
                'id' => 122,
                'code' => 'LS',
                'name' => 'Lesotho',
                'phoneCode' => 266
            ],
            [
                'id' => 123,
                'code' => 'LR',
                'name' => 'Liberia',
                'phoneCode' => 231
            ],
            [
                'id' => 124,
                'code' => 'LY',
                'name' => 'Libya',
                'phoneCode' => 218
            ],
            [
                'id' => 125,
                'code' => 'LI',
                'name' => 'Liechtenstein',
                'phoneCode' => 423
            ],
            [
                'id' => 126,
                'code' => 'LT',
                'name' => 'Lithuania',
                'phoneCode' => 370
            ],
            [
                'id' => 127,
                'code' => 'LU',
                'name' => 'Luxembourg',
                'phoneCode' => 352
            ],
            [
                'id' => 128,
                'code' => 'MO',
                'name' => 'Macau S.A.R.',
                'phoneCode' => 853
            ],
            [
                'id' => 129,
                'code' => 'MK',
                'name' => 'Macedonia',
                'phoneCode' => 389
            ],
            [
                'id' => 130,
                'code' => 'MG',
                'name' => 'Madagascar',
                'phoneCode' => 261
            ],
            [
                'id' => 131,
                'code' => 'MW',
                'name' => 'Malawi',
                'phoneCode' => 265
            ],
            [
                'id' => 132,
                'code' => 'MY',
                'name' => 'Malaysia',
                'phoneCode' => 60
            ],
            [
                'id' => 133,
                'code' => 'MV',
                'name' => 'Maldives',
                'phoneCode' => 960
            ],
            [
                'id' => 134,
                'code' => 'ML',
                'name' => 'Mali',
                'phoneCode' => 223
            ],
            [
                'id' => 135,
                'code' => 'MT',
                'name' => 'Malta',
                'phoneCode' => 356
            ],
            [
                'id' => 136,
                'code' => 'XM',
                'name' => 'Man (Isle of)',
                'phoneCode' => 44
            ],
            [
                'id' => 137,
                'code' => 'MH',
                'name' => 'Marshall Islands',
                'phoneCode' => 692
            ],
            [
                'id' => 138,
                'code' => 'MQ',
                'name' => 'Martinique',
                'phoneCode' => 596
            ],
            [
                'id' => 139,
                'code' => 'MR',
                'name' => 'Mauritania',
                'phoneCode' => 222
            ],
            [
                'id' => 140,
                'code' => 'MU',
                'name' => 'Mauritius',
                'phoneCode' => 230
            ],
            [
                'id' => 141,
                'code' => 'YT',
                'name' => 'Mayotte',
                'phoneCode' => 269
            ],
            [
                'id' => 142,
                'code' => 'MX',
                'name' => 'Mexico',
                'phoneCode' => 52
            ],
            [
                'id' => 143,
                'code' => 'FM',
                'name' => 'Micronesia',
                'phoneCode' => 691
            ],
            [
                'id' => 144,
                'code' => 'MD',
                'name' => 'Moldova',
                'phoneCode' => 373
            ],
            [
                'id' => 145,
                'code' => 'MC',
                'name' => 'Monaco',
                'phoneCode' => 377
            ],
            [
                'id' => 146,
                'code' => 'MN',
                'name' => 'Mongolia',
                'phoneCode' => 976
            ],
            [
                'id' => 147,
                'code' => 'MS',
                'name' => 'Montserrat',
                'phoneCode' => 1664
            ],
            [
                'id' => 148,
                'code' => 'MA',
                'name' => 'Morocco',
                'phoneCode' => 212
            ],
            [
                'id' => 149,
                'code' => 'MZ',
                'name' => 'Mozambique',
                'phoneCode' => 258
            ],
            [
                'id' => 150,
                'code' => 'MM',
                'name' => 'Myanmar',
                'phoneCode' => 95
            ],
            [
                'id' => 151,
                'code' => 'NA',
                'name' => 'Namibia',
                'phoneCode' => 264
            ],
            [
                'id' => 152,
                'code' => 'NR',
                'name' => 'Nauru',
                'phoneCode' => 674
            ],
            [
                'id' => 153,
                'code' => 'NP',
                'name' => 'Nepal',
                'phoneCode' => 977
            ],
            [
                'id' => 154,
                'code' => 'AN',
                'name' => 'Netherlands Antilles',
                'phoneCode' => 599
            ],
            [
                'id' => 155,
                'code' => 'NL',
                'name' => 'Netherlands The',
                'phoneCode' => 31
            ],
            [
                'id' => 156,
                'code' => 'NC',
                'name' => 'New Caledonia',
                'phoneCode' => 687
            ],
            [
                'id' => 157,
                'code' => 'NZ',
                'name' => 'New Zealand',
                'phoneCode' => 64
            ],
            [
                'id' => 158,
                'code' => 'NI',
                'name' => 'Nicaragua',
                'phoneCode' => 505
            ],
            [
                'id' => 159,
                'code' => 'NE',
                'name' => 'Niger',
                'phoneCode' => 227
            ],
            [
                'id' => 160,
                'code' => 'NG',
                'name' => 'Nigeria',
                'phoneCode' => 234
            ],
            [
                'id' => 161,
                'code' => 'NU',
                'name' => 'Niue',
                'phoneCode' => 683
            ],
            [
                'id' => 162,
                'code' => 'NF',
                'name' => 'Norfolk Island',
                'phoneCode' => 672
            ],
            [
                'id' => 163,
                'code' => 'MP',
                'name' => 'Northern Mariana Islands',
                'phoneCode' => 1670
            ],
            [
                'id' => 164,
                'code' => 'NO',
                'name' => 'Norway',
                'phoneCode' => 47
            ],
            [
                'id' => 165,
                'code' => 'OM',
                'name' => 'Oman',
                'phoneCode' => 968
            ],
            [
                'id' => 166,
                'code' => 'PK',
                'name' => 'Pakistan',
                'phoneCode' => 92
            ],
            [
                'id' => 167,
                'code' => 'PW',
                'name' => 'Palau',
                'phoneCode' => 680
            ],
            [
                'id' => 168,
                'code' => 'PS',
                'name' => 'Palestinian Territory Occupied',
                'phoneCode' => 970
            ],
            [
                'id' => 169,
                'code' => 'PA',
                'name' => 'Panama',
                'phoneCode' => 507
            ],
            [
                'id' => 170,
                'code' => 'PG',
                'name' => 'Papua new Guinea',
                'phoneCode' => 675
            ],
            [
                'id' => 171,
                'code' => 'PY',
                'name' => 'Paraguay',
                'phoneCode' => 595
            ],
            [
                'id' => 172,
                'code' => 'PE',
                'name' => 'Peru',
                'phoneCode' => 51
            ],
            [
                'id' => 173,
                'code' => 'PH',
                'name' => 'Philippines',
                'phoneCode' => 63
            ],
            [
                'id' => 174,
                'code' => 'PN',
                'name' => 'Pitcairn Island',
                'phoneCode' => 0
            ],
            [
                'id' => 175,
                'code' => 'PL',
                'name' => 'Poland',
                'phoneCode' => 48
            ],
            [
                'id' => 176,
                'code' => 'PT',
                'name' => 'Portugal',
                'phoneCode' => 351
            ],
            [
                'id' => 177,
                'code' => 'PR',
                'name' => 'Puerto Rico',
                'phoneCode' => 1787
            ],
            [
                'id' => 178,
                'code' => 'QA',
                'name' => 'Qatar',
                'phoneCode' => 974
            ],
            [
                'id' => 179,
                'code' => 'RE',
                'name' => 'Reunion',
                'phoneCode' => 262
            ],
            [
                'id' => 180,
                'code' => 'RO',
                'name' => 'Romania',
                'phoneCode' => 40
            ],
            [
                'id' => 181,
                'code' => 'RU',
                'name' => 'Russia',
                'phoneCode' => 70
            ],
            [
                'id' => 182,
                'code' => 'RW',
                'name' => 'Rwanda',
                'phoneCode' => 250
            ],
            [
                'id' => 183,
                'code' => 'SH',
                'name' => 'Saint Helena',
                'phoneCode' => 290
            ],
            [
                'id' => 184,
                'code' => 'KN',
                'name' => 'Saint Kitts And Nevis',
                'phoneCode' => 1869
            ],
            [
                'id' => 185,
                'code' => 'LC',
                'name' => 'Saint Lucia',
                'phoneCode' => 1758
            ],
            [
                'id' => 186,
                'code' => 'PM',
                'name' => 'Saint Pierre and Miquelon',
                'phoneCode' => 508
            ],
            [
                'id' => 187,
                'code' => 'VC',
                'name' => 'Saint Vincent And The Grenadines',
                'phoneCode' => 1784
            ],
            [
                'id' => 188,
                'code' => 'WS',
                'name' => 'Samoa',
                'phoneCode' => 684
            ],
            [
                'id' => 189,
                'code' => 'SM',
                'name' => 'San Marino',
                'phoneCode' => 378
            ],
            [
                'id' => 190,
                'code' => 'ST',
                'name' => 'Sao Tome and Principe',
                'phoneCode' => 239
            ],
            [
                'id' => 191,
                'code' => 'SA',
                'name' => 'Saudi Arabia',
                'phoneCode' => 966
            ],
            [
                'id' => 192,
                'code' => 'SN',
                'name' => 'Senegal',
                'phoneCode' => 221
            ],
            [
                'id' => 193,
                'code' => 'RS',
                'name' => 'Serbia',
                'phoneCode' => 381
            ],
            [
                'id' => 194,
                'code' => 'SC',
                'name' => 'Seychelles',
                'phoneCode' => 248
            ],
            [
                'id' => 195,
                'code' => 'SL',
                'name' => 'Sierra Leone',
                'phoneCode' => 232
            ],
            [
                'id' => 196,
                'code' => 'SG',
                'name' => 'Singapore',
                'phoneCode' => 65
            ],
            [
                'id' => 197,
                'code' => 'SK',
                'name' => 'Slovakia',
                'phoneCode' => 421
            ],
            [
                'id' => 198,
                'code' => 'SI',
                'name' => 'Slovenia',
                'phoneCode' => 386
            ],
            [
                'id' => 199,
                'code' => 'XG',
                'name' => 'Smaller Territories of the UK',
                'phoneCode' => 44
            ],
            [
                'id' => 200,
                'code' => 'SB',
                'name' => 'Solomon Islands',
                'phoneCode' => 677
            ],
            [
                'id' => 201,
                'code' => 'SO',
                'name' => 'Somalia',
                'phoneCode' => 252
            ],
            [
                'id' => 202,
                'code' => 'ZA',
                'name' => 'South Africa',
                'phoneCode' => 27
            ],
            [
                'id' => 203,
                'code' => 'GS',
                'name' => 'South Georgia',
                'phoneCode' => 0
            ],
            [
                'id' => 204,
                'code' => 'SS',
                'name' => 'South Sudan',
                'phoneCode' => 211
            ],
            [
                'id' => 205,
                'code' => 'ES',
                'name' => 'Spain',
                'phoneCode' => 34
            ],
            [
                'id' => 206,
                'code' => 'LK',
                'name' => 'Sri Lanka',
                'phoneCode' => 94
            ],
            [
                'id' => 207,
                'code' => 'SD',
                'name' => 'Sudan',
                'phoneCode' => 249
            ],
            [
                'id' => 208,
                'code' => 'SR',
                'name' => 'Suriname',
                'phoneCode' => 597
            ],
            [
                'id' => 209,
                'code' => 'SJ',
                'name' => 'Svalbard And Jan Mayen Islands',
                'phoneCode' => 47
            ],
            [
                'id' => 210,
                'code' => 'SZ',
                'name' => 'Swaziland',
                'phoneCode' => 268
            ],
            [
                'id' => 211,
                'code' => 'SE',
                'name' => 'Sweden',
                'phoneCode' => 46
            ],
            [
                'id' => 212,
                'code' => 'CH',
                'name' => 'Switzerland',
                'phoneCode' => 41
            ],
            [
                'id' => 213,
                'code' => 'SY',
                'name' => 'Syria',
                'phoneCode' => 963
            ],
            [
                'id' => 214,
                'code' => 'TW',
                'name' => 'Taiwan',
                'phoneCode' => 886
            ],
            [
                'id' => 215,
                'code' => 'TJ',
                'name' => 'Tajikistan',
                'phoneCode' => 992
            ],
            [
                'id' => 216,
                'code' => 'TZ',
                'name' => 'Tanzania',
                'phoneCode' => 255
            ],
            [
                'id' => 217,
                'code' => 'TH',
                'name' => 'Thailand',
                'phoneCode' => 66
            ],
            [
                'id' => 218,
                'code' => 'TG',
                'name' => 'Togo',
                'phoneCode' => 228
            ],
            [
                'id' => 219,
                'code' => 'TK',
                'name' => 'Tokelau',
                'phoneCode' => 690
            ],
            [
                'id' => 220,
                'code' => 'TO',
                'name' => 'Tonga',
                'phoneCode' => 676
            ],
            [
                'id' => 221,
                'code' => 'TT',
                'name' => 'Trinidad And Tobago',
                'phoneCode' => 1868
            ],
            [
                'id' => 222,
                'code' => 'TN',
                'name' => 'Tunisia',
                'phoneCode' => 216
            ],
            [
                'id' => 223,
                'code' => 'TR',
                'name' => 'Turkey',
                'phoneCode' => 90
            ],
            [
                'id' => 224,
                'code' => 'TM',
                'name' => 'Turkmenistan',
                'phoneCode' => 7370
            ],
            [
                'id' => 225,
                'code' => 'TC',
                'name' => 'Turks And Caicos Islands',
                'phoneCode' => 1649
            ],
            [
                'id' => 226,
                'code' => 'TV',
                'name' => 'Tuvalu',
                'phoneCode' => 688
            ],
            [
                'id' => 227,
                'code' => 'UG',
                'name' => 'Uganda',
                'phoneCode' => 256
            ],
            [
                'id' => 228,
                'code' => 'UA',
                'name' => 'Ukraine',
                'phoneCode' => 380
            ],
            [
                'id' => 229,
                'code' => 'AE',
                'name' => 'United Arab Emirates',
                'phoneCode' => 971
            ],
            [
                'id' => 230,
                'code' => 'GB',
                'name' => 'United Kingdom',
                'phoneCode' => 44
            ],
            [
                'id' => 231,
                'code' => 'US',
                'name' => 'United States',
                'phoneCode' => 1
            ],
            [
                'id' => 232,
                'code' => 'UM',
                'name' => 'United States Minor Outlying Islands',
                'phoneCode' => 1
            ],
            [
                'id' => 233,
                'code' => 'UY',
                'name' => 'Uruguay',
                'phoneCode' => 598
            ],
            [
                'id' => 234,
                'code' => 'UZ',
                'name' => 'Uzbekistan',
                'phoneCode' => 998
            ],
            [
                'id' => 235,
                'code' => 'VU',
                'name' => 'Vanuatu',
                'phoneCode' => 678
            ],
            [
                'id' => 236,
                'code' => 'VA',
                'name' => 'Vatican City State (Holy See)',
                'phoneCode' => 39
            ],
            [
                'id' => 237,
                'code' => 'VE',
                'name' => 'Venezuela',
                'phoneCode' => 58
            ],
            [
                'id' => 238,
                'code' => 'VN',
                'name' => 'Vietnam',
                'phoneCode' => 84
            ],
            [
                'id' => 239,
                'code' => 'VG',
                'name' => 'Virgin Islands (British)',
                'phoneCode' => 1284
            ],
            [
                'id' => 240,
                'code' => 'VI',
                'name' => 'Virgin Islands (US)',
                'phoneCode' => 1340
            ],
            [
                'id' => 241,
                'code' => 'WF',
                'name' => 'Wallis And Futuna Islands',
                'phoneCode' => 681
            ],
            [
                'id' => 242,
                'code' => 'EH',
                'name' => 'Western Sahara',
                'phoneCode' => 212
            ],
            [
                'id' => 243,
                'code' => 'YE',
                'name' => 'Yemen',
                'phoneCode' => 967
            ],
            [
                'id' => 244,
                'code' => 'YU',
                'name' => 'Yugoslavia',
                'phoneCode' => 38
            ],
            [
                'id' => 245,
                'code' => 'ZM',
                'name' => 'Zambia',
                'phoneCode' => 260
            ],
            [
                'id' => 246,
                'code' => 'ZW',
                'name' => 'Zimbabwe',
                'phoneCode' => 26
            ],
        ];
        Country::insert($countries);
    }
}
