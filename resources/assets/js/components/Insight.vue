<template>
    <div>
        <div class="row text-center mt-3">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        Revenue Change
                    </div>
                    <div class="card-body p-2">
                        <label for="revenue_from">From</label>
                        <div class="range-slider">
                            <input name="revenue_from" id="revenue_from" class="range-slider__range" type="range" v-model="revenueFrom" min="-20" max="20">
                            <span class="range-slider__value">{{revenueFrom}}</span>
                        </div>
                        <label for="revenue_to">To</label>
                        <div class="range-slider">
                            <input name="revenue_to" id="revenue_to" class="range-slider__range" type="range" v-model="revenueTo" min="-20" max="20">
                            <span class="range-slider__value">{{revenueTo}}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        Client Size
                    </div>
                    <div class="card-body p-2">
                        <label for="size_from">From</label>
                        <div class="range-slider">
                            <input name="size_from" id="size_from" class="range-slider__range" type="range" v-model="sizeFrom" min="0" max="3000">
                            <span class="range-slider__value">{{sizeFrom}}</span>
                        </div>
                        <label for="size_to">To</label>
                        <div class="range-slider">
                            <input name="size_to" id="size_to" class="range-slider__range" type="range" v-model="sizeTo" min="0" max="3000">
                            <span class="range-slider__value">{{sizeTo}}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="table-responsive mt-3">
            <table class="table table-striped table-bordered table-sm table-hover">
                <thead>
                <tr>
                    <th>Client</th>
                    <th class="blackboard-insight-share-column">Revenue Change</th>
                    <th class="blackboard-insight-size-column">Client Size</th>
                    <th class="blackboard-insight-action-column">Action</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="client in slicedClients">
                    <td><a :href="getGoogleLink(client.name)">{{client.name}}</a></td>
                    <td :class="[(client.revenueChange>=0) ? 'text-success' : 'text-danger']"><i class="fa fa-line-chart"></i> {{client.revenueChange}}%</td>
                    <td class="text-muted"><i class="fa fa-users"></i> {{client.size}}</td>
                    <td>
                        <a :href="getAddLink(client.name)" class="btn btn-sm btn-outline-primary" target="_blank"><i class="fa fa-plus"></i> Add</a>
                        <a :href="getGoogleLink(client.name)" class="btn btn-sm btn-outline-primary" target="_blank"><i class="fa fa-google"></i> Search</a>
                    </td>
                </tr>
                <tr v-for="n in listSize-slicedClients.length" class="blackboard-insight-client-row">
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                </tbody>
            </table>

            <small class="text-muted">Found <b>{{filteredClients.length}}</b> clients matching those criteria.</small>

            <ul>

            </ul>

        </div>
    </div>
</template>

<script>
    export default {
        props: {
            addUserLink: {
                default: ''
            },
        },
        data() {
            return {
                sizeFrom: 50,
                sizeTo: 1000,
                revenueFrom: 0,
                revenueTo: 10,
                listSize: 12,
                clients: [
                    {
                        "id": 6,
                        "name": "Conn, Hilpert and Pfannerstill",
                        "revenueChange": -19,
                        "size": 2342
                    },
                    {
                        "id": 372,
                        "name": "Gusikowski-Terry",
                        "revenueChange": -19,
                        "size": 1102
                    },
                    {
                        "id": 376,
                        "name": "Hessel-Adams",
                        "revenueChange": -19,
                        "size": 2904
                    },
                    {
                        "id": 139,
                        "name": "Schuppe, Rodriguez and Kunze",
                        "revenueChange": -19,
                        "size": 2352
                    },
                    {
                        "id": 175,
                        "name": "Padberg-Nicolas",
                        "revenueChange": -19,
                        "size": 1874
                    },
                    {
                        "id": 280,
                        "name": "Kshlerin-Ortiz",
                        "revenueChange": -18,
                        "size": 994
                    },
                    {
                        "id": 70,
                        "name": "Bechtelar-Haley",
                        "revenueChange": -18,
                        "size": 2198
                    },
                    {
                        "id": 375,
                        "name": "McLaughlin, Larkin and O'Keefe",
                        "revenueChange": -18,
                        "size": 1917
                    },
                    {
                        "id": 243,
                        "name": "Kris Group",
                        "revenueChange": -18,
                        "size": 2384
                    },
                    {
                        "id": 282,
                        "name": "Sanford-Franecki",
                        "revenueChange": -17,
                        "size": 2913
                    },
                    {
                        "id": 29,
                        "name": "Stokes Ltd",
                        "revenueChange": -17,
                        "size": 956
                    },
                    {
                        "id": 65,
                        "name": "Lind LLC",
                        "revenueChange": -17,
                        "size": 2934
                    },
                    {
                        "id": 94,
                        "name": "Monahan, Wolff and Fadel",
                        "revenueChange": -17,
                        "size": 1398
                    },
                    {
                        "id": 364,
                        "name": "Treutel, Jacobs and Larson",
                        "revenueChange": -17,
                        "size": 1939
                    },
                    {
                        "id": 474,
                        "name": "Ferry, Stiedemann and Monahan",
                        "revenueChange": -17,
                        "size": 1161
                    },
                    {
                        "id": 311,
                        "name": "King Group",
                        "revenueChange": -16,
                        "size": 1632
                    },
                    {
                        "id": 124,
                        "name": "Satterfield, Windler and Johnson",
                        "revenueChange": -16,
                        "size": 980
                    },
                    {
                        "id": 206,
                        "name": "Smitham, Nikolaus and Bayer",
                        "revenueChange": -16,
                        "size": 2975
                    },
                    {
                        "id": 462,
                        "name": "Jakubowski LLC",
                        "revenueChange": -16,
                        "size": 780
                    },
                    {
                        "id": 238,
                        "name": "Marks-Brekke",
                        "revenueChange": -16,
                        "size": 850
                    },
                    {
                        "id": 494,
                        "name": "Botsford-Flatley",
                        "revenueChange": -16,
                        "size": 1619
                    },
                    {
                        "id": 320,
                        "name": "Schroeder-Cassin",
                        "revenueChange": -15,
                        "size": 2889
                    },
                    {
                        "id": 69,
                        "name": "Rosenbaum, Denesik and Yundt",
                        "revenueChange": -15,
                        "size": 2103
                    },
                    {
                        "id": 363,
                        "name": "Marks, Orn and Considine",
                        "revenueChange": -15,
                        "size": 2820
                    },
                    {
                        "id": 367,
                        "name": "Altenwerth LLC",
                        "revenueChange": -15,
                        "size": 1810
                    },
                    {
                        "id": 112,
                        "name": "Bergnaum, Ortiz and Becker",
                        "revenueChange": -15,
                        "size": 284
                    },
                    {
                        "id": 392,
                        "name": "Hegmann, Vandervort and Orn",
                        "revenueChange": -15,
                        "size": 2926
                    },
                    {
                        "id": 144,
                        "name": "Stroman Group",
                        "revenueChange": -15,
                        "size": 2401
                    },
                    {
                        "id": 418,
                        "name": "Fadel and Sons",
                        "revenueChange": -15,
                        "size": 1888
                    },
                    {
                        "id": 208,
                        "name": "Heathcote PLC",
                        "revenueChange": -15,
                        "size": 2137
                    },
                    {
                        "id": 224,
                        "name": "Wilkinson, Crist and Lowe",
                        "revenueChange": -15,
                        "size": 1430
                    },
                    {
                        "id": 480,
                        "name": "Welch-Trantow",
                        "revenueChange": -15,
                        "size": 1172
                    },
                    {
                        "id": 498,
                        "name": "Spinka LLC",
                        "revenueChange": -15,
                        "size": 2675
                    },
                    {
                        "id": 250,
                        "name": "Heidenreich-Daniel",
                        "revenueChange": -15,
                        "size": 2451
                    },
                    {
                        "id": 254,
                        "name": "Cormier-Block",
                        "revenueChange": -15,
                        "size": 2037
                    },
                    {
                        "id": 273,
                        "name": "Willms, Hyatt and Cruickshank",
                        "revenueChange": -14,
                        "size": 1187
                    },
                    {
                        "id": 26,
                        "name": "Upton PLC",
                        "revenueChange": -14,
                        "size": 2291
                    },
                    {
                        "id": 73,
                        "name": "Bechtelar, Reynolds and Krajcik",
                        "revenueChange": -14,
                        "size": 1864
                    },
                    {
                        "id": 80,
                        "name": "Corwin-Aufderhar",
                        "revenueChange": -14,
                        "size": 365
                    },
                    {
                        "id": 381,
                        "name": "Windler Group",
                        "revenueChange": -14,
                        "size": 1806
                    },
                    {
                        "id": 416,
                        "name": "O'Keefe-Ortiz",
                        "revenueChange": -14,
                        "size": 1968
                    },
                    {
                        "id": 446,
                        "name": "Stroman, Predovic and Pagac",
                        "revenueChange": -14,
                        "size": 1554
                    },
                    {
                        "id": 212,
                        "name": "Schuster LLC",
                        "revenueChange": -14,
                        "size": 424
                    },
                    {
                        "id": 245,
                        "name": "Towne-Kilback",
                        "revenueChange": -14,
                        "size": 2416
                    },
                    {
                        "id": 252,
                        "name": "Kuvalis, Moore and Dicki",
                        "revenueChange": -14,
                        "size": 1286
                    },
                    {
                        "id": 253,
                        "name": "Hayes-Swift",
                        "revenueChange": -14,
                        "size": 1625
                    },
                    {
                        "id": 271,
                        "name": "Kozey-Huel",
                        "revenueChange": -13,
                        "size": 2400
                    },
                    {
                        "id": 275,
                        "name": "Kunze, Sanford and Funk",
                        "revenueChange": -13,
                        "size": 1427
                    },
                    {
                        "id": 288,
                        "name": "Corwin Group",
                        "revenueChange": -13,
                        "size": 2812
                    },
                    {
                        "id": 324,
                        "name": "Pacocha Inc",
                        "revenueChange": -13,
                        "size": 1812
                    },
                    {
                        "id": 326,
                        "name": "Parker Ltd",
                        "revenueChange": -13,
                        "size": 1011
                    },
                    {
                        "id": 87,
                        "name": "Turner Ltd",
                        "revenueChange": -13,
                        "size": 448
                    },
                    {
                        "id": 426,
                        "name": "Langosh, Schamberger and Farrell",
                        "revenueChange": -13,
                        "size": 819
                    },
                    {
                        "id": 436,
                        "name": "Gerhold, Friesen and Keeling",
                        "revenueChange": -13,
                        "size": 962
                    },
                    {
                        "id": 289,
                        "name": "Torphy PLC",
                        "revenueChange": -12,
                        "size": 178
                    },
                    {
                        "id": 34,
                        "name": "Crona, Goldner and Oberbrunner",
                        "revenueChange": -12,
                        "size": 2611
                    },
                    {
                        "id": 306,
                        "name": "Rodriguez PLC",
                        "revenueChange": -12,
                        "size": 2569
                    },
                    {
                        "id": 353,
                        "name": "Kris Inc",
                        "revenueChange": -12,
                        "size": 2305
                    },
                    {
                        "id": 355,
                        "name": "Pfeffer PLC",
                        "revenueChange": -12,
                        "size": 1760
                    },
                    {
                        "id": 396,
                        "name": "Spinka PLC",
                        "revenueChange": -12,
                        "size": 80
                    },
                    {
                        "id": 419,
                        "name": "Lowe, Hudson and Strosin",
                        "revenueChange": -12,
                        "size": 1507
                    },
                    {
                        "id": 427,
                        "name": "Feest, Labadie and Dickinson",
                        "revenueChange": -12,
                        "size": 1617
                    },
                    {
                        "id": 433,
                        "name": "Bechtelar and Sons",
                        "revenueChange": -12,
                        "size": 558
                    },
                    {
                        "id": 213,
                        "name": "Hahn Inc",
                        "revenueChange": -12,
                        "size": 1542
                    },
                    {
                        "id": 11,
                        "name": "Senger Group",
                        "revenueChange": -11,
                        "size": 1230
                    },
                    {
                        "id": 290,
                        "name": "Kihn, Schumm and Reichel",
                        "revenueChange": -11,
                        "size": 602
                    },
                    {
                        "id": 51,
                        "name": "Lueilwitz-Koelpin",
                        "revenueChange": -11,
                        "size": 1549
                    },
                    {
                        "id": 96,
                        "name": "Lebsack LLC",
                        "revenueChange": -11,
                        "size": 772
                    },
                    {
                        "id": 115,
                        "name": "Grady and Sons",
                        "revenueChange": -11,
                        "size": 710
                    },
                    {
                        "id": 126,
                        "name": "Friesen-Bruen",
                        "revenueChange": -11,
                        "size": 1638
                    },
                    {
                        "id": 171,
                        "name": "Cassin-Prosacco",
                        "revenueChange": -11,
                        "size": 2955
                    },
                    {
                        "id": 185,
                        "name": "Ledner and Sons",
                        "revenueChange": -11,
                        "size": 741
                    },
                    {
                        "id": 441,
                        "name": "Volkman Inc",
                        "revenueChange": -11,
                        "size": 2738
                    },
                    {
                        "id": 187,
                        "name": "Harris, Strosin and Kassulke",
                        "revenueChange": -11,
                        "size": 976
                    },
                    {
                        "id": 192,
                        "name": "Nienow, Bartell and Grimes",
                        "revenueChange": -11,
                        "size": 2557
                    },
                    {
                        "id": 195,
                        "name": "VonRueden, Greenfelder and Kling",
                        "revenueChange": -11,
                        "size": 2410
                    },
                    {
                        "id": 226,
                        "name": "Block PLC",
                        "revenueChange": -11,
                        "size": 2211
                    },
                    {
                        "id": 229,
                        "name": "Terry, Strosin and Pagac",
                        "revenueChange": -11,
                        "size": 2042
                    },
                    {
                        "id": 486,
                        "name": "Toy Inc",
                        "revenueChange": -11,
                        "size": 1869
                    },
                    {
                        "id": 236,
                        "name": "Hyatt, Turcotte and McLaughlin",
                        "revenueChange": -11,
                        "size": 2375
                    },
                    {
                        "id": 248,
                        "name": "Crooks-Huel",
                        "revenueChange": -11,
                        "size": 1116
                    },
                    {
                        "id": 255,
                        "name": "Walker Ltd",
                        "revenueChange": -11,
                        "size": 2557
                    },
                    {
                        "id": 258,
                        "name": "Becker-Hilpert",
                        "revenueChange": -10,
                        "size": 744
                    },
                    {
                        "id": 291,
                        "name": "Ankunding PLC",
                        "revenueChange": -10,
                        "size": 1275
                    },
                    {
                        "id": 297,
                        "name": "Tremblay, Rowe and Blick",
                        "revenueChange": -10,
                        "size": 2912
                    },
                    {
                        "id": 318,
                        "name": "Gusikowski, Kuhic and Wolf",
                        "revenueChange": -10,
                        "size": 1437
                    },
                    {
                        "id": 325,
                        "name": "Thiel-Kunze",
                        "revenueChange": -10,
                        "size": 2060
                    },
                    {
                        "id": 79,
                        "name": "Kovacek-Hermiston",
                        "revenueChange": -10,
                        "size": 2194
                    },
                    {
                        "id": 85,
                        "name": "Kerluke, Skiles and Bogisich",
                        "revenueChange": -10,
                        "size": 1456
                    },
                    {
                        "id": 88,
                        "name": "Hauck LLC",
                        "revenueChange": -10,
                        "size": 1205
                    },
                    {
                        "id": 108,
                        "name": "Streich LLC",
                        "revenueChange": -10,
                        "size": 1572
                    },
                    {
                        "id": 109,
                        "name": "Jaskolski Ltd",
                        "revenueChange": -10,
                        "size": 343
                    },
                    {
                        "id": 110,
                        "name": "Kuphal and Sons",
                        "revenueChange": -10,
                        "size": 2173
                    },
                    {
                        "id": 377,
                        "name": "Yundt Group",
                        "revenueChange": -10,
                        "size": 2500
                    },
                    {
                        "id": 131,
                        "name": "Goldner, Ullrich and Kunze",
                        "revenueChange": -10,
                        "size": 2420
                    },
                    {
                        "id": 390,
                        "name": "Beatty-Mayert",
                        "revenueChange": -10,
                        "size": 1937
                    },
                    {
                        "id": 149,
                        "name": "Schultz Group",
                        "revenueChange": -10,
                        "size": 1151
                    },
                    {
                        "id": 163,
                        "name": "Collins-Towne",
                        "revenueChange": -10,
                        "size": 1575
                    },
                    {
                        "id": 2,
                        "name": "Bruen, Herzog and Marquardt",
                        "revenueChange": -9,
                        "size": 48
                    },
                    {
                        "id": 278,
                        "name": "Blick LLC",
                        "revenueChange": -9,
                        "size": 1940
                    },
                    {
                        "id": 25,
                        "name": "Grant Ltd",
                        "revenueChange": -9,
                        "size": 944
                    },
                    {
                        "id": 293,
                        "name": "Koelpin, Nitzsche and Stiedemann",
                        "revenueChange": -9,
                        "size": 2879
                    },
                    {
                        "id": 53,
                        "name": "Goodwin, Wiegand and Medhurst",
                        "revenueChange": -9,
                        "size": 761
                    },
                    {
                        "id": 58,
                        "name": "Walsh, Gulgowski and Feest",
                        "revenueChange": -9,
                        "size": 1480
                    },
                    {
                        "id": 63,
                        "name": "Moen, Kshlerin and Kunze",
                        "revenueChange": -9,
                        "size": 1658
                    },
                    {
                        "id": 328,
                        "name": "Abernathy Group",
                        "revenueChange": -9,
                        "size": 2232
                    },
                    {
                        "id": 341,
                        "name": "Bode-Waelchi",
                        "revenueChange": -9,
                        "size": 47
                    },
                    {
                        "id": 91,
                        "name": "Wolf-Collier",
                        "revenueChange": -9,
                        "size": 1777
                    },
                    {
                        "id": 183,
                        "name": "Barrows and Sons",
                        "revenueChange": -9,
                        "size": 2279
                    },
                    {
                        "id": 455,
                        "name": "Sanford, Feest and Wunsch",
                        "revenueChange": -9,
                        "size": 2852
                    },
                    {
                        "id": 205,
                        "name": "Larson-Sporer",
                        "revenueChange": -9,
                        "size": 2747
                    },
                    {
                        "id": 475,
                        "name": "Macejkovic LLC",
                        "revenueChange": -9,
                        "size": 2856
                    },
                    {
                        "id": 228,
                        "name": "Towne-Rippin",
                        "revenueChange": -9,
                        "size": 49
                    },
                    {
                        "id": 231,
                        "name": "Boyle Ltd",
                        "revenueChange": -9,
                        "size": 1761
                    },
                    {
                        "id": 234,
                        "name": "Corwin-Grady",
                        "revenueChange": -9,
                        "size": 1604
                    },
                    {
                        "id": 4,
                        "name": "Veum, Dach and Runolfsdottir",
                        "revenueChange": -8,
                        "size": 2487
                    },
                    {
                        "id": 261,
                        "name": "Wiza Ltd",
                        "revenueChange": -8,
                        "size": 1435
                    },
                    {
                        "id": 19,
                        "name": "Satterfield, Franecki and Schmitt",
                        "revenueChange": -8,
                        "size": 630
                    },
                    {
                        "id": 23,
                        "name": "Prohaska-Champlin",
                        "revenueChange": -8,
                        "size": 2850
                    },
                    {
                        "id": 39,
                        "name": "Sawayn Ltd",
                        "revenueChange": -8,
                        "size": 1570
                    },
                    {
                        "id": 314,
                        "name": "Champlin-Strosin",
                        "revenueChange": -8,
                        "size": 121
                    },
                    {
                        "id": 86,
                        "name": "Blick, Orn and Dooley",
                        "revenueChange": -8,
                        "size": 425
                    },
                    {
                        "id": 356,
                        "name": "Boehm-Emmerich",
                        "revenueChange": -8,
                        "size": 1219
                    },
                    {
                        "id": 368,
                        "name": "Lehner, Reichert and Kertzmann",
                        "revenueChange": -8,
                        "size": 1784
                    },
                    {
                        "id": 371,
                        "name": "Jacobi, Sawayn and Reynolds",
                        "revenueChange": -8,
                        "size": 285
                    },
                    {
                        "id": 184,
                        "name": "Bergnaum Inc",
                        "revenueChange": -8,
                        "size": 350
                    },
                    {
                        "id": 186,
                        "name": "Nader-Bailey",
                        "revenueChange": -8,
                        "size": 1985
                    },
                    {
                        "id": 214,
                        "name": "Blanda-Hills",
                        "revenueChange": -8,
                        "size": 2903
                    },
                    {
                        "id": 472,
                        "name": "Medhurst PLC",
                        "revenueChange": -8,
                        "size": 1667
                    },
                    {
                        "id": 235,
                        "name": "Hoppe Group",
                        "revenueChange": -8,
                        "size": 1435
                    },
                    {
                        "id": 491,
                        "name": "Paucek PLC",
                        "revenueChange": -8,
                        "size": 1483
                    },
                    {
                        "id": 244,
                        "name": "Jaskolski Ltd",
                        "revenueChange": -8,
                        "size": 662
                    },
                    {
                        "id": 257,
                        "name": "Langworth, Smith and Goodwin",
                        "revenueChange": -7,
                        "size": 2234
                    },
                    {
                        "id": 37,
                        "name": "Cruickshank and Sons",
                        "revenueChange": -7,
                        "size": 2914
                    },
                    {
                        "id": 309,
                        "name": "Orn-Schowalter",
                        "revenueChange": -7,
                        "size": 1052
                    },
                    {
                        "id": 334,
                        "name": "Muller and Sons",
                        "revenueChange": -7,
                        "size": 2554
                    },
                    {
                        "id": 98,
                        "name": "Johnson PLC",
                        "revenueChange": -7,
                        "size": 1150
                    },
                    {
                        "id": 365,
                        "name": "Russel LLC",
                        "revenueChange": -7,
                        "size": 587
                    },
                    {
                        "id": 114,
                        "name": "Kuhn-Thompson",
                        "revenueChange": -7,
                        "size": 2574
                    },
                    {
                        "id": 134,
                        "name": "O'Reilly-Predovic",
                        "revenueChange": -7,
                        "size": 2219
                    },
                    {
                        "id": 137,
                        "name": "Moen-Legros",
                        "revenueChange": -7,
                        "size": 1845
                    },
                    {
                        "id": 148,
                        "name": "Moen-Zemlak",
                        "revenueChange": -7,
                        "size": 2123
                    },
                    {
                        "id": 407,
                        "name": "Feest-Johnson",
                        "revenueChange": -7,
                        "size": 1796
                    },
                    {
                        "id": 413,
                        "name": "Kautzer Group",
                        "revenueChange": -7,
                        "size": 1944
                    },
                    {
                        "id": 200,
                        "name": "Hills-Raynor",
                        "revenueChange": -7,
                        "size": 553
                    },
                    {
                        "id": 230,
                        "name": "Ledner, Barrows and Schiller",
                        "revenueChange": -7,
                        "size": 1796
                    },
                    {
                        "id": 22,
                        "name": "Botsford LLC",
                        "revenueChange": -6,
                        "size": 524
                    },
                    {
                        "id": 284,
                        "name": "Reynolds-Lockman",
                        "revenueChange": -6,
                        "size": 2505
                    },
                    {
                        "id": 339,
                        "name": "Johnston-Lebsack",
                        "revenueChange": -6,
                        "size": 2498
                    },
                    {
                        "id": 102,
                        "name": "Barton, Haley and Haley",
                        "revenueChange": -6,
                        "size": 2665
                    },
                    {
                        "id": 138,
                        "name": "Johns Ltd",
                        "revenueChange": -6,
                        "size": 2970
                    },
                    {
                        "id": 399,
                        "name": "O'Keefe-Langworth",
                        "revenueChange": -6,
                        "size": 2601
                    },
                    {
                        "id": 153,
                        "name": "Carroll-Halvorson",
                        "revenueChange": -6,
                        "size": 2892
                    },
                    {
                        "id": 194,
                        "name": "Gorczany Ltd",
                        "revenueChange": -6,
                        "size": 816
                    },
                    {
                        "id": 467,
                        "name": "Padberg-Pfannerstill",
                        "revenueChange": -6,
                        "size": 475
                    },
                    {
                        "id": 219,
                        "name": "Rippin, Zemlak and Reichert",
                        "revenueChange": -6,
                        "size": 609
                    },
                    {
                        "id": 478,
                        "name": "Bode-Bins",
                        "revenueChange": -6,
                        "size": 1411
                    },
                    {
                        "id": 482,
                        "name": "Olson, Terry and Boyle",
                        "revenueChange": -6,
                        "size": 2797
                    },
                    {
                        "id": 492,
                        "name": "Hegmann, Dickens and Doyle",
                        "revenueChange": -6,
                        "size": 1520
                    },
                    {
                        "id": 12,
                        "name": "Spinka, Jacobs and Bechtelar",
                        "revenueChange": -5,
                        "size": 1280
                    },
                    {
                        "id": 49,
                        "name": "Morar LLC",
                        "revenueChange": -5,
                        "size": 2432
                    },
                    {
                        "id": 68,
                        "name": "Macejkovic-Zulauf",
                        "revenueChange": -5,
                        "size": 2645
                    },
                    {
                        "id": 72,
                        "name": "Abbott-Hoppe",
                        "revenueChange": -5,
                        "size": 456
                    },
                    {
                        "id": 331,
                        "name": "Hermiston-Bashirian",
                        "revenueChange": -5,
                        "size": 2426
                    },
                    {
                        "id": 332,
                        "name": "Jacobi-Shields",
                        "revenueChange": -5,
                        "size": 2259
                    },
                    {
                        "id": 337,
                        "name": "Gleason-Ledner",
                        "revenueChange": -5,
                        "size": 1897
                    },
                    {
                        "id": 346,
                        "name": "Walker Ltd",
                        "revenueChange": -5,
                        "size": 348
                    },
                    {
                        "id": 357,
                        "name": "Haley, Kiehn and Jacobs",
                        "revenueChange": -5,
                        "size": 533
                    },
                    {
                        "id": 106,
                        "name": "Konopelski Inc",
                        "revenueChange": -5,
                        "size": 2130
                    },
                    {
                        "id": 369,
                        "name": "Swaniawski, Fritsch and Hahn",
                        "revenueChange": -5,
                        "size": 104
                    },
                    {
                        "id": 380,
                        "name": "Carroll Ltd",
                        "revenueChange": -5,
                        "size": 2221
                    },
                    {
                        "id": 130,
                        "name": "Gleichner, Denesik and Dickens",
                        "revenueChange": -5,
                        "size": 515
                    },
                    {
                        "id": 150,
                        "name": "McKenzie Inc",
                        "revenueChange": -5,
                        "size": 1890
                    },
                    {
                        "id": 423,
                        "name": "Sawayn-Gibson",
                        "revenueChange": -5,
                        "size": 1188
                    },
                    {
                        "id": 173,
                        "name": "Feeney-Lang",
                        "revenueChange": -5,
                        "size": 865
                    },
                    {
                        "id": 444,
                        "name": "Medhurst-Dietrich",
                        "revenueChange": -5,
                        "size": 2912
                    },
                    {
                        "id": 201,
                        "name": "Jacobi-Wintheiser",
                        "revenueChange": -5,
                        "size": 2681
                    },
                    {
                        "id": 216,
                        "name": "Legros-Simonis",
                        "revenueChange": -5,
                        "size": 1809
                    },
                    {
                        "id": 483,
                        "name": "Rodriguez, Wilderman and Halvorson",
                        "revenueChange": -5,
                        "size": 541
                    },
                    {
                        "id": 485,
                        "name": "Roob-Marvin",
                        "revenueChange": -5,
                        "size": 1695
                    },
                    {
                        "id": 251,
                        "name": "Jacobi-Thompson",
                        "revenueChange": -5,
                        "size": 1208
                    },
                    {
                        "id": 268,
                        "name": "Bechtelar, Sauer and Abbott",
                        "revenueChange": -4,
                        "size": 91
                    },
                    {
                        "id": 269,
                        "name": "Greenholt-Weber",
                        "revenueChange": -4,
                        "size": 1507
                    },
                    {
                        "id": 20,
                        "name": "Bartoletti-Farrell",
                        "revenueChange": -4,
                        "size": 2836
                    },
                    {
                        "id": 47,
                        "name": "Jerde Inc",
                        "revenueChange": -4,
                        "size": 1377
                    },
                    {
                        "id": 54,
                        "name": "Eichmann, O'Connell and Luettgen",
                        "revenueChange": -4,
                        "size": 608
                    },
                    {
                        "id": 351,
                        "name": "Gleason LLC",
                        "revenueChange": -4,
                        "size": 1306
                    },
                    {
                        "id": 99,
                        "name": "Watsica-McCullough",
                        "revenueChange": -4,
                        "size": 2153
                    },
                    {
                        "id": 129,
                        "name": "Schuster-Emmerich",
                        "revenueChange": -4,
                        "size": 732
                    },
                    {
                        "id": 388,
                        "name": "Rogahn, Hickle and Langworth",
                        "revenueChange": -4,
                        "size": 440
                    },
                    {
                        "id": 395,
                        "name": "Koss Ltd",
                        "revenueChange": -4,
                        "size": 1541
                    },
                    {
                        "id": 398,
                        "name": "Satterfield, Lubowitz and Stehr",
                        "revenueChange": -4,
                        "size": 1726
                    },
                    {
                        "id": 146,
                        "name": "Crona, Runolfsson and Glover",
                        "revenueChange": -4,
                        "size": 324
                    },
                    {
                        "id": 404,
                        "name": "Johns LLC",
                        "revenueChange": -4,
                        "size": 1324
                    },
                    {
                        "id": 160,
                        "name": "McCullough and Sons",
                        "revenueChange": -4,
                        "size": 135
                    },
                    {
                        "id": 169,
                        "name": "Moen-McGlynn",
                        "revenueChange": -4,
                        "size": 2326
                    },
                    {
                        "id": 170,
                        "name": "Schowalter, Schroeder and O'Reilly",
                        "revenueChange": -4,
                        "size": 785
                    },
                    {
                        "id": 442,
                        "name": "Kuhlman, Gleason and Mayer",
                        "revenueChange": -4,
                        "size": 1499
                    },
                    {
                        "id": 495,
                        "name": "Heidenreich, VonRueden and Kirlin",
                        "revenueChange": -4,
                        "size": 1632
                    },
                    {
                        "id": 263,
                        "name": "Crooks, Botsford and Padberg",
                        "revenueChange": -3,
                        "size": 249
                    },
                    {
                        "id": 272,
                        "name": "Quigley-Lowe",
                        "revenueChange": -3,
                        "size": 2102
                    },
                    {
                        "id": 17,
                        "name": "Aufderhar, Stokes and Zieme",
                        "revenueChange": -3,
                        "size": 1313
                    },
                    {
                        "id": 18,
                        "name": "Kerluke-Armstrong",
                        "revenueChange": -3,
                        "size": 79
                    },
                    {
                        "id": 38,
                        "name": "Walter, Thompson and Zieme",
                        "revenueChange": -3,
                        "size": 716
                    },
                    {
                        "id": 295,
                        "name": "Schowalter LLC",
                        "revenueChange": -3,
                        "size": 1176
                    },
                    {
                        "id": 41,
                        "name": "Goodwin LLC",
                        "revenueChange": -3,
                        "size": 107
                    },
                    {
                        "id": 43,
                        "name": "Quitzon-Wilkinson",
                        "revenueChange": -3,
                        "size": 1165
                    },
                    {
                        "id": 44,
                        "name": "Leuschke Inc",
                        "revenueChange": -3,
                        "size": 811
                    },
                    {
                        "id": 335,
                        "name": "Klein-Bashirian",
                        "revenueChange": -3,
                        "size": 2150
                    },
                    {
                        "id": 82,
                        "name": "Schaefer, Balistreri and Kub",
                        "revenueChange": -3,
                        "size": 2034
                    },
                    {
                        "id": 362,
                        "name": "Pacocha-Yost",
                        "revenueChange": -3,
                        "size": 1421
                    },
                    {
                        "id": 378,
                        "name": "Rosenbaum-Schulist",
                        "revenueChange": -3,
                        "size": 672
                    },
                    {
                        "id": 158,
                        "name": "Streich PLC",
                        "revenueChange": -3,
                        "size": 122
                    },
                    {
                        "id": 417,
                        "name": "Yost, Hauck and Kohler",
                        "revenueChange": -3,
                        "size": 1236
                    },
                    {
                        "id": 167,
                        "name": "Thiel, Bogan and Mayer",
                        "revenueChange": -3,
                        "size": 2543
                    },
                    {
                        "id": 168,
                        "name": "Spencer-Johnson",
                        "revenueChange": -3,
                        "size": 2057
                    },
                    {
                        "id": 435,
                        "name": "Rice Inc",
                        "revenueChange": -3,
                        "size": 41
                    },
                    {
                        "id": 445,
                        "name": "Gottlieb Ltd",
                        "revenueChange": -3,
                        "size": 983
                    },
                    {
                        "id": 197,
                        "name": "Schmidt LLC",
                        "revenueChange": -3,
                        "size": 823
                    },
                    {
                        "id": 460,
                        "name": "Denesik, Braun and Runolfsson",
                        "revenueChange": -3,
                        "size": 1477
                    },
                    {
                        "id": 463,
                        "name": "Mills Group",
                        "revenueChange": -3,
                        "size": 724
                    },
                    {
                        "id": 220,
                        "name": "Ullrich and Sons",
                        "revenueChange": -3,
                        "size": 550
                    },
                    {
                        "id": 489,
                        "name": "Crist LLC",
                        "revenueChange": -3,
                        "size": 1971
                    },
                    {
                        "id": 246,
                        "name": "Donnelly-Rath",
                        "revenueChange": -3,
                        "size": 1061
                    },
                    {
                        "id": 15,
                        "name": "Mayert, Kertzmann and Daugherty",
                        "revenueChange": -2,
                        "size": 2308
                    },
                    {
                        "id": 21,
                        "name": "Sporer-Bechtelar",
                        "revenueChange": -2,
                        "size": 2078
                    },
                    {
                        "id": 36,
                        "name": "Gaylord-Toy",
                        "revenueChange": -2,
                        "size": 500
                    },
                    {
                        "id": 55,
                        "name": "Schmeler Inc",
                        "revenueChange": -2,
                        "size": 412
                    },
                    {
                        "id": 316,
                        "name": "Kuvalis, Stracke and Haley",
                        "revenueChange": -2,
                        "size": 904
                    },
                    {
                        "id": 322,
                        "name": "Hansen-Beahan",
                        "revenueChange": -2,
                        "size": 499
                    },
                    {
                        "id": 71,
                        "name": "Quigley, Parker and Crooks",
                        "revenueChange": -2,
                        "size": 919
                    },
                    {
                        "id": 336,
                        "name": "Bode, Leuschke and Hahn",
                        "revenueChange": -2,
                        "size": 2892
                    },
                    {
                        "id": 84,
                        "name": "Hansen Inc",
                        "revenueChange": -2,
                        "size": 585
                    },
                    {
                        "id": 111,
                        "name": "O'Reilly PLC",
                        "revenueChange": -2,
                        "size": 1385
                    },
                    {
                        "id": 145,
                        "name": "Haley-Reynolds",
                        "revenueChange": -2,
                        "size": 2593
                    },
                    {
                        "id": 405,
                        "name": "Marquardt-Wisoky",
                        "revenueChange": -2,
                        "size": 515
                    },
                    {
                        "id": 164,
                        "name": "Waelchi Ltd",
                        "revenueChange": -2,
                        "size": 416
                    },
                    {
                        "id": 166,
                        "name": "Cormier, Predovic and Kemmer",
                        "revenueChange": -2,
                        "size": 1530
                    },
                    {
                        "id": 432,
                        "name": "Gerlach, Kovacek and Hilpert",
                        "revenueChange": -2,
                        "size": 2975
                    },
                    {
                        "id": 178,
                        "name": "Doyle, Little and Pouros",
                        "revenueChange": -2,
                        "size": 1604
                    },
                    {
                        "id": 440,
                        "name": "Stroman-Gleason",
                        "revenueChange": -2,
                        "size": 744
                    },
                    {
                        "id": 447,
                        "name": "Runolfsdottir Ltd",
                        "revenueChange": -2,
                        "size": 865
                    },
                    {
                        "id": 458,
                        "name": "Goldner-Jacobs",
                        "revenueChange": -2,
                        "size": 1551
                    },
                    {
                        "id": 225,
                        "name": "Ferry, Ledner and Marquardt",
                        "revenueChange": -2,
                        "size": 1332
                    },
                    {
                        "id": 227,
                        "name": "Stark-Mueller",
                        "revenueChange": -2,
                        "size": 1380
                    },
                    {
                        "id": 239,
                        "name": "Bashirian Ltd",
                        "revenueChange": -2,
                        "size": 2641
                    },
                    {
                        "id": 500,
                        "name": "Swaniawski-Konopelski",
                        "revenueChange": -2,
                        "size": 1271
                    },
                    {
                        "id": 264,
                        "name": "Sanford-Langworth",
                        "revenueChange": -1,
                        "size": 1251
                    },
                    {
                        "id": 285,
                        "name": "Barrows-McKenzie",
                        "revenueChange": -1,
                        "size": 1985
                    },
                    {
                        "id": 31,
                        "name": "Parisian-Paucek",
                        "revenueChange": -1,
                        "size": 695
                    },
                    {
                        "id": 33,
                        "name": "Mayer-Parisian",
                        "revenueChange": -1,
                        "size": 1424
                    },
                    {
                        "id": 301,
                        "name": "Mohr-Howell",
                        "revenueChange": -1,
                        "size": 331
                    },
                    {
                        "id": 305,
                        "name": "Eichmann-Fadel",
                        "revenueChange": -1,
                        "size": 8
                    },
                    {
                        "id": 90,
                        "name": "Padberg-Runolfsson",
                        "revenueChange": -1,
                        "size": 1383
                    },
                    {
                        "id": 349,
                        "name": "Sipes-Marks",
                        "revenueChange": -1,
                        "size": 264
                    },
                    {
                        "id": 370,
                        "name": "Hauck, Brown and Boehm",
                        "revenueChange": -1,
                        "size": 1089
                    },
                    {
                        "id": 120,
                        "name": "Kassulke, Jakubowski and Bailey",
                        "revenueChange": -1,
                        "size": 2765
                    },
                    {
                        "id": 136,
                        "name": "Oberbrunner Ltd",
                        "revenueChange": -1,
                        "size": 2141
                    },
                    {
                        "id": 393,
                        "name": "Gerhold, Hermann and Lehner",
                        "revenueChange": -1,
                        "size": 434
                    },
                    {
                        "id": 401,
                        "name": "Medhurst, O'Keefe and Frami",
                        "revenueChange": -1,
                        "size": 1484
                    },
                    {
                        "id": 161,
                        "name": "Prosacco PLC",
                        "revenueChange": -1,
                        "size": 155
                    },
                    {
                        "id": 162,
                        "name": "Casper-Considine",
                        "revenueChange": -1,
                        "size": 1473
                    },
                    {
                        "id": 453,
                        "name": "Nader Group",
                        "revenueChange": -1,
                        "size": 325
                    },
                    {
                        "id": 209,
                        "name": "Runolfsson, Rowe and Beahan",
                        "revenueChange": -1,
                        "size": 2791
                    },
                    {
                        "id": 211,
                        "name": "Roob, Becker and O'Connell",
                        "revenueChange": -1,
                        "size": 1996
                    },
                    {
                        "id": 469,
                        "name": "Kuhn, Walter and Green",
                        "revenueChange": -1,
                        "size": 2888
                    },
                    {
                        "id": 470,
                        "name": "Skiles and Sons",
                        "revenueChange": -1,
                        "size": 1020
                    },
                    {
                        "id": 249,
                        "name": "Purdy Inc",
                        "revenueChange": -1,
                        "size": 118
                    },
                    {
                        "id": 3,
                        "name": "Bergnaum Group",
                        "revenueChange": 1,
                        "size": 2468
                    },
                    {
                        "id": 27,
                        "name": "Fahey, Maggio and Osinski",
                        "revenueChange": 1,
                        "size": 2580
                    },
                    {
                        "id": 32,
                        "name": "O'Keefe-Ratke",
                        "revenueChange": 1,
                        "size": 2057
                    },
                    {
                        "id": 35,
                        "name": "Hamill PLC",
                        "revenueChange": 1,
                        "size": 764
                    },
                    {
                        "id": 296,
                        "name": "Rempel and Sons",
                        "revenueChange": 1,
                        "size": 2830
                    },
                    {
                        "id": 52,
                        "name": "Koch, Wisozk and Kuhic",
                        "revenueChange": 1,
                        "size": 2011
                    },
                    {
                        "id": 60,
                        "name": "Zboncak-Pacocha",
                        "revenueChange": 1,
                        "size": 501
                    },
                    {
                        "id": 64,
                        "name": "Bernier, Krajcik and Carroll",
                        "revenueChange": 1,
                        "size": 2138
                    },
                    {
                        "id": 330,
                        "name": "Corkery-Wisozk",
                        "revenueChange": 1,
                        "size": 211
                    },
                    {
                        "id": 92,
                        "name": "Sporer-Hagenes",
                        "revenueChange": 1,
                        "size": 525
                    },
                    {
                        "id": 95,
                        "name": "Blanda and Sons",
                        "revenueChange": 1,
                        "size": 2584
                    },
                    {
                        "id": 118,
                        "name": "Schaden-Bartell",
                        "revenueChange": 1,
                        "size": 2469
                    },
                    {
                        "id": 128,
                        "name": "Bergnaum PLC",
                        "revenueChange": 1,
                        "size": 1439
                    },
                    {
                        "id": 384,
                        "name": "Wilderman, Mante and Rath",
                        "revenueChange": 1,
                        "size": 679
                    },
                    {
                        "id": 386,
                        "name": "Gislason, Hoppe and O'Hara",
                        "revenueChange": 1,
                        "size": 2145
                    },
                    {
                        "id": 143,
                        "name": "Runolfsson, Kertzmann and Will",
                        "revenueChange": 1,
                        "size": 2950
                    },
                    {
                        "id": 408,
                        "name": "Rath-Jones",
                        "revenueChange": 1,
                        "size": 2178
                    },
                    {
                        "id": 176,
                        "name": "Langworth, Russel and Heidenreich",
                        "revenueChange": 1,
                        "size": 962
                    },
                    {
                        "id": 190,
                        "name": "McCullough PLC",
                        "revenueChange": 1,
                        "size": 691
                    },
                    {
                        "id": 476,
                        "name": "Ernser-Deckow",
                        "revenueChange": 1,
                        "size": 825
                    },
                    {
                        "id": 221,
                        "name": "Kilback-Harris",
                        "revenueChange": 1,
                        "size": 2849
                    },
                    {
                        "id": 479,
                        "name": "Wilkinson-Rowe",
                        "revenueChange": 1,
                        "size": 1101
                    },
                    {
                        "id": 488,
                        "name": "Bauch-Brekke",
                        "revenueChange": 1,
                        "size": 119
                    },
                    {
                        "id": 7,
                        "name": "Pfeffer-Connelly",
                        "revenueChange": 2,
                        "size": 2480
                    },
                    {
                        "id": 276,
                        "name": "Sporer, Rippin and Emard",
                        "revenueChange": 2,
                        "size": 2983
                    },
                    {
                        "id": 283,
                        "name": "Feest-Batz",
                        "revenueChange": 2,
                        "size": 525
                    },
                    {
                        "id": 292,
                        "name": "Jacobs-Bins",
                        "revenueChange": 2,
                        "size": 2230
                    },
                    {
                        "id": 307,
                        "name": "Block-Block",
                        "revenueChange": 2,
                        "size": 223
                    },
                    {
                        "id": 310,
                        "name": "Weissnat PLC",
                        "revenueChange": 2,
                        "size": 460
                    },
                    {
                        "id": 56,
                        "name": "Lemke, Kuhic and Bernier",
                        "revenueChange": 2,
                        "size": 1446
                    },
                    {
                        "id": 313,
                        "name": "Kuhlman, Hirthe and Homenick",
                        "revenueChange": 2,
                        "size": 1817
                    },
                    {
                        "id": 319,
                        "name": "Stoltenberg-Greenfelder",
                        "revenueChange": 2,
                        "size": 1894
                    },
                    {
                        "id": 343,
                        "name": "Sipes-Brown",
                        "revenueChange": 2,
                        "size": 1316
                    },
                    {
                        "id": 344,
                        "name": "Bailey PLC",
                        "revenueChange": 2,
                        "size": 2862
                    },
                    {
                        "id": 347,
                        "name": "Lowe Inc",
                        "revenueChange": 2,
                        "size": 2017
                    },
                    {
                        "id": 350,
                        "name": "Kling, Hammes and Blick",
                        "revenueChange": 2,
                        "size": 2951
                    },
                    {
                        "id": 359,
                        "name": "Oberbrunner-Witting",
                        "revenueChange": 2,
                        "size": 321
                    },
                    {
                        "id": 361,
                        "name": "Satterfield-Dietrich",
                        "revenueChange": 2,
                        "size": 1846
                    },
                    {
                        "id": 403,
                        "name": "Ward Ltd",
                        "revenueChange": 2,
                        "size": 1992
                    },
                    {
                        "id": 157,
                        "name": "Toy-Beer",
                        "revenueChange": 2,
                        "size": 2446
                    },
                    {
                        "id": 420,
                        "name": "Brekke-Schowalter",
                        "revenueChange": 2,
                        "size": 2677
                    },
                    {
                        "id": 461,
                        "name": "Walker, Bartell and Greenholt",
                        "revenueChange": 2,
                        "size": 2786
                    },
                    {
                        "id": 471,
                        "name": "Kirlin PLC",
                        "revenueChange": 2,
                        "size": 2627
                    },
                    {
                        "id": 217,
                        "name": "Klocko, Lubowitz and Moore",
                        "revenueChange": 2,
                        "size": 1247
                    },
                    {
                        "id": 10,
                        "name": "Zieme-Langworth",
                        "revenueChange": 3,
                        "size": 260
                    },
                    {
                        "id": 16,
                        "name": "Frami, Dibbert and Feil",
                        "revenueChange": 3,
                        "size": 681
                    },
                    {
                        "id": 304,
                        "name": "Frami Group",
                        "revenueChange": 3,
                        "size": 1212
                    },
                    {
                        "id": 317,
                        "name": "Hamill-Reichert",
                        "revenueChange": 3,
                        "size": 1081
                    },
                    {
                        "id": 352,
                        "name": "Schmidt, Tillman and Kozey",
                        "revenueChange": 3,
                        "size": 1437
                    },
                    {
                        "id": 97,
                        "name": "Jakubowski-Dickens",
                        "revenueChange": 3,
                        "size": 1339
                    },
                    {
                        "id": 101,
                        "name": "Kozey LLC",
                        "revenueChange": 3,
                        "size": 21
                    },
                    {
                        "id": 105,
                        "name": "McKenzie-O'Kon",
                        "revenueChange": 3,
                        "size": 1346
                    },
                    {
                        "id": 113,
                        "name": "Schmidt, Casper and Mills",
                        "revenueChange": 3,
                        "size": 1305
                    },
                    {
                        "id": 119,
                        "name": "Bergnaum-Bogisich",
                        "revenueChange": 3,
                        "size": 1621
                    },
                    {
                        "id": 394,
                        "name": "Stamm Group",
                        "revenueChange": 3,
                        "size": 2873
                    },
                    {
                        "id": 421,
                        "name": "Wolff-McKenzie",
                        "revenueChange": 3,
                        "size": 355
                    },
                    {
                        "id": 193,
                        "name": "Hettinger LLC",
                        "revenueChange": 3,
                        "size": 2344
                    },
                    {
                        "id": 459,
                        "name": "Donnelly Inc",
                        "revenueChange": 3,
                        "size": 2493
                    },
                    {
                        "id": 207,
                        "name": "Kuhic, Wunsch and McDermott",
                        "revenueChange": 3,
                        "size": 2837
                    },
                    {
                        "id": 464,
                        "name": "Hartmann-Beier",
                        "revenueChange": 3,
                        "size": 1004
                    },
                    {
                        "id": 210,
                        "name": "Sipes-Gerhold",
                        "revenueChange": 3,
                        "size": 2960
                    },
                    {
                        "id": 218,
                        "name": "Klein, Green and Hammes",
                        "revenueChange": 3,
                        "size": 2301
                    },
                    {
                        "id": 223,
                        "name": "Hyatt-Kuhic",
                        "revenueChange": 3,
                        "size": 2000
                    },
                    {
                        "id": 484,
                        "name": "Cummings and Sons",
                        "revenueChange": 3,
                        "size": 1398
                    },
                    {
                        "id": 262,
                        "name": "Homenick, McClure and Durgan",
                        "revenueChange": 4,
                        "size": 911
                    },
                    {
                        "id": 267,
                        "name": "Muller, Klein and Zulauf",
                        "revenueChange": 4,
                        "size": 47
                    },
                    {
                        "id": 294,
                        "name": "Fahey PLC",
                        "revenueChange": 4,
                        "size": 1232
                    },
                    {
                        "id": 59,
                        "name": "Windler, Gottlieb and Pagac",
                        "revenueChange": 4,
                        "size": 363
                    },
                    {
                        "id": 75,
                        "name": "Klocko, Lind and Marvin",
                        "revenueChange": 4,
                        "size": 2894
                    },
                    {
                        "id": 373,
                        "name": "Eichmann-Hilll",
                        "revenueChange": 4,
                        "size": 928
                    },
                    {
                        "id": 121,
                        "name": "Fadel-Strosin",
                        "revenueChange": 4,
                        "size": 2267
                    },
                    {
                        "id": 387,
                        "name": "Simonis, Schultz and Stanton",
                        "revenueChange": 4,
                        "size": 739
                    },
                    {
                        "id": 155,
                        "name": "Mante Inc",
                        "revenueChange": 4,
                        "size": 871
                    },
                    {
                        "id": 443,
                        "name": "Weissnat Ltd",
                        "revenueChange": 4,
                        "size": 93
                    },
                    {
                        "id": 188,
                        "name": "Bartell-Mayert",
                        "revenueChange": 4,
                        "size": 2351
                    },
                    {
                        "id": 199,
                        "name": "O'Kon-Lubowitz",
                        "revenueChange": 4,
                        "size": 2769
                    },
                    {
                        "id": 457,
                        "name": "Ruecker PLC",
                        "revenueChange": 4,
                        "size": 2741
                    },
                    {
                        "id": 265,
                        "name": "Lemke-Mann",
                        "revenueChange": 5,
                        "size": 2112
                    },
                    {
                        "id": 13,
                        "name": "Harris Ltd",
                        "revenueChange": 5,
                        "size": 252
                    },
                    {
                        "id": 279,
                        "name": "Rempel, Gleason and Schaefer",
                        "revenueChange": 5,
                        "size": 2100
                    },
                    {
                        "id": 42,
                        "name": "Williamson-Wintheiser",
                        "revenueChange": 5,
                        "size": 878
                    },
                    {
                        "id": 46,
                        "name": "Muller-Osinski",
                        "revenueChange": 5,
                        "size": 2268
                    },
                    {
                        "id": 302,
                        "name": "O'Kon, Kerluke and Bailey",
                        "revenueChange": 5,
                        "size": 1681
                    },
                    {
                        "id": 152,
                        "name": "Leuschke Inc",
                        "revenueChange": 5,
                        "size": 1117
                    },
                    {
                        "id": 156,
                        "name": "McCullough and Sons",
                        "revenueChange": 5,
                        "size": 2799
                    },
                    {
                        "id": 165,
                        "name": "Toy, Wyman and Nitzsche",
                        "revenueChange": 5,
                        "size": 1078
                    },
                    {
                        "id": 172,
                        "name": "Walsh-Monahan",
                        "revenueChange": 5,
                        "size": 2442
                    },
                    {
                        "id": 430,
                        "name": "Lemke, Morar and Marks",
                        "revenueChange": 5,
                        "size": 1227
                    },
                    {
                        "id": 437,
                        "name": "Rippin and Sons",
                        "revenueChange": 5,
                        "size": 965
                    },
                    {
                        "id": 182,
                        "name": "Wuckert Group",
                        "revenueChange": 5,
                        "size": 2426
                    },
                    {
                        "id": 439,
                        "name": "Ward, Mante and O'Conner",
                        "revenueChange": 5,
                        "size": 1289
                    },
                    {
                        "id": 450,
                        "name": "Hyatt-Bruen",
                        "revenueChange": 5,
                        "size": 77
                    },
                    {
                        "id": 203,
                        "name": "Pfannerstill and Sons",
                        "revenueChange": 5,
                        "size": 2490
                    },
                    {
                        "id": 473,
                        "name": "O'Conner-Ratke",
                        "revenueChange": 5,
                        "size": 2617
                    },
                    {
                        "id": 477,
                        "name": "Hartmann-Kshlerin",
                        "revenueChange": 5,
                        "size": 2055
                    },
                    {
                        "id": 233,
                        "name": "Schamberger Ltd",
                        "revenueChange": 5,
                        "size": 2179
                    },
                    {
                        "id": 496,
                        "name": "Beer Group",
                        "revenueChange": 5,
                        "size": 2114
                    },
                    {
                        "id": 499,
                        "name": "Fay, Abernathy and Considine",
                        "revenueChange": 5,
                        "size": 286
                    },
                    {
                        "id": 14,
                        "name": "Stehr-Gusikowski",
                        "revenueChange": 6,
                        "size": 925
                    },
                    {
                        "id": 270,
                        "name": "Romaguera, Lind and Miller",
                        "revenueChange": 6,
                        "size": 1877
                    },
                    {
                        "id": 287,
                        "name": "Conroy-Schaefer",
                        "revenueChange": 6,
                        "size": 1466
                    },
                    {
                        "id": 299,
                        "name": "Kozey PLC",
                        "revenueChange": 6,
                        "size": 2751
                    },
                    {
                        "id": 48,
                        "name": "Hirthe, Kassulke and Schroeder",
                        "revenueChange": 6,
                        "size": 2094
                    },
                    {
                        "id": 50,
                        "name": "Wilkinson and Sons",
                        "revenueChange": 6,
                        "size": 2137
                    },
                    {
                        "id": 57,
                        "name": "Schoen-Pouros",
                        "revenueChange": 6,
                        "size": 2702
                    },
                    {
                        "id": 103,
                        "name": "Franecki, Buckridge and Russel",
                        "revenueChange": 6,
                        "size": 1553
                    },
                    {
                        "id": 374,
                        "name": "Labadie-Rosenbaum",
                        "revenueChange": 6,
                        "size": 2621
                    },
                    {
                        "id": 383,
                        "name": "Moen Group",
                        "revenueChange": 6,
                        "size": 2952
                    },
                    {
                        "id": 177,
                        "name": "Rau and Sons",
                        "revenueChange": 6,
                        "size": 756
                    },
                    {
                        "id": 241,
                        "name": "Lesch Group",
                        "revenueChange": 6,
                        "size": 2572
                    },
                    {
                        "id": 1,
                        "name": "Lind-Beatty",
                        "revenueChange": 7,
                        "size": 714
                    },
                    {
                        "id": 77,
                        "name": "Schiller, Rath and Brakus",
                        "revenueChange": 7,
                        "size": 1398
                    },
                    {
                        "id": 333,
                        "name": "Cassin PLC",
                        "revenueChange": 7,
                        "size": 469
                    },
                    {
                        "id": 360,
                        "name": "O'Keefe-Spinka",
                        "revenueChange": 7,
                        "size": 586
                    },
                    {
                        "id": 123,
                        "name": "Klein-Hirthe",
                        "revenueChange": 7,
                        "size": 362
                    },
                    {
                        "id": 379,
                        "name": "Kautzer LLC",
                        "revenueChange": 7,
                        "size": 1656
                    },
                    {
                        "id": 385,
                        "name": "Lindgren, Lebsack and Crona",
                        "revenueChange": 7,
                        "size": 2172
                    },
                    {
                        "id": 412,
                        "name": "Kunde, Bashirian and Quigley",
                        "revenueChange": 7,
                        "size": 2164
                    },
                    {
                        "id": 425,
                        "name": "Heaney, Ratke and Ortiz",
                        "revenueChange": 7,
                        "size": 2486
                    },
                    {
                        "id": 189,
                        "name": "Block, Hamill and Jakubowski",
                        "revenueChange": 7,
                        "size": 2336
                    },
                    {
                        "id": 448,
                        "name": "Brown-Batz",
                        "revenueChange": 7,
                        "size": 1324
                    },
                    {
                        "id": 452,
                        "name": "Hirthe PLC",
                        "revenueChange": 7,
                        "size": 2286
                    },
                    {
                        "id": 466,
                        "name": "Schimmel, Torphy and Mertz",
                        "revenueChange": 7,
                        "size": 1325
                    },
                    {
                        "id": 490,
                        "name": "Metz, Senger and Bahringer",
                        "revenueChange": 7,
                        "size": 1214
                    },
                    {
                        "id": 259,
                        "name": "Bode Inc",
                        "revenueChange": 8,
                        "size": 761
                    },
                    {
                        "id": 66,
                        "name": "Mante, Marvin and Hauck",
                        "revenueChange": 8,
                        "size": 2411
                    },
                    {
                        "id": 323,
                        "name": "Heller Group",
                        "revenueChange": 8,
                        "size": 608
                    },
                    {
                        "id": 78,
                        "name": "Brown-Tremblay",
                        "revenueChange": 8,
                        "size": 53
                    },
                    {
                        "id": 100,
                        "name": "Altenwerth, Hoeger and Bartoletti",
                        "revenueChange": 8,
                        "size": 2559
                    },
                    {
                        "id": 127,
                        "name": "Kutch, Murazik and Walsh",
                        "revenueChange": 8,
                        "size": 751
                    },
                    {
                        "id": 133,
                        "name": "Smitham and Sons",
                        "revenueChange": 8,
                        "size": 2302
                    },
                    {
                        "id": 181,
                        "name": "VonRueden and Sons",
                        "revenueChange": 8,
                        "size": 2434
                    },
                    {
                        "id": 454,
                        "name": "Purdy Ltd",
                        "revenueChange": 8,
                        "size": 2374
                    },
                    {
                        "id": 465,
                        "name": "Altenwerth LLC",
                        "revenueChange": 8,
                        "size": 1095
                    },
                    {
                        "id": 8,
                        "name": "Baumbach, Fadel and Rohan",
                        "revenueChange": 9,
                        "size": 833
                    },
                    {
                        "id": 9,
                        "name": "Schmidt and Sons",
                        "revenueChange": 9,
                        "size": 2936
                    },
                    {
                        "id": 277,
                        "name": "Kreiger-Greenholt",
                        "revenueChange": 9,
                        "size": 963
                    },
                    {
                        "id": 24,
                        "name": "Rempel, Keeling and Dicki",
                        "revenueChange": 9,
                        "size": 480
                    },
                    {
                        "id": 315,
                        "name": "Lubowitz, Maggio and Schmeler",
                        "revenueChange": 9,
                        "size": 903
                    },
                    {
                        "id": 321,
                        "name": "Frami, Grady and Hahn",
                        "revenueChange": 9,
                        "size": 1399
                    },
                    {
                        "id": 81,
                        "name": "Cruickshank Group",
                        "revenueChange": 9,
                        "size": 2174
                    },
                    {
                        "id": 132,
                        "name": "Torp Ltd",
                        "revenueChange": 9,
                        "size": 2560
                    },
                    {
                        "id": 410,
                        "name": "Quitzon-Mohr",
                        "revenueChange": 9,
                        "size": 821
                    },
                    {
                        "id": 180,
                        "name": "Yundt, Keebler and Stoltenberg",
                        "revenueChange": 9,
                        "size": 622
                    },
                    {
                        "id": 481,
                        "name": "Prosacco Group",
                        "revenueChange": 9,
                        "size": 842
                    },
                    {
                        "id": 240,
                        "name": "Jakubowski, Hamill and Block",
                        "revenueChange": 9,
                        "size": 363
                    },
                    {
                        "id": 497,
                        "name": "Schultz, Monahan and Leffler",
                        "revenueChange": 9,
                        "size": 2897
                    },
                    {
                        "id": 5,
                        "name": "Hackett, Okuneva and Kshlerin",
                        "revenueChange": 10,
                        "size": 2413
                    },
                    {
                        "id": 266,
                        "name": "Schultz, Osinski and Brown",
                        "revenueChange": 10,
                        "size": 975
                    },
                    {
                        "id": 61,
                        "name": "Corkery Ltd",
                        "revenueChange": 10,
                        "size": 2951
                    },
                    {
                        "id": 329,
                        "name": "Willms, Kuvalis and Considine",
                        "revenueChange": 10,
                        "size": 2741
                    },
                    {
                        "id": 107,
                        "name": "Wyman, Marvin and Rempel",
                        "revenueChange": 10,
                        "size": 1860
                    },
                    {
                        "id": 141,
                        "name": "Schuster, Bergnaum and Ryan",
                        "revenueChange": 10,
                        "size": 1652
                    },
                    {
                        "id": 406,
                        "name": "Nienow Ltd",
                        "revenueChange": 10,
                        "size": 71
                    },
                    {
                        "id": 196,
                        "name": "Douglas, Wilkinson and Bashirian",
                        "revenueChange": 10,
                        "size": 1743
                    },
                    {
                        "id": 247,
                        "name": "Kerluke, Lehner and Funk",
                        "revenueChange": 10,
                        "size": 2540
                    },
                    {
                        "id": 28,
                        "name": "Hane, Daniel and Jerde",
                        "revenueChange": 11,
                        "size": 249
                    },
                    {
                        "id": 40,
                        "name": "Hagenes-Erdman",
                        "revenueChange": 11,
                        "size": 2232
                    },
                    {
                        "id": 116,
                        "name": "O'Reilly-Friesen",
                        "revenueChange": 11,
                        "size": 498
                    },
                    {
                        "id": 391,
                        "name": "Collins LLC",
                        "revenueChange": 11,
                        "size": 55
                    },
                    {
                        "id": 159,
                        "name": "Towne Group",
                        "revenueChange": 11,
                        "size": 217
                    },
                    {
                        "id": 204,
                        "name": "Doyle-Satterfield",
                        "revenueChange": 11,
                        "size": 2773
                    },
                    {
                        "id": 298,
                        "name": "Bednar-Rau",
                        "revenueChange": 12,
                        "size": 2273
                    },
                    {
                        "id": 303,
                        "name": "Wilderman, King and Fritsch",
                        "revenueChange": 12,
                        "size": 392
                    },
                    {
                        "id": 76,
                        "name": "Spencer-Toy",
                        "revenueChange": 12,
                        "size": 1406
                    },
                    {
                        "id": 411,
                        "name": "Murray Ltd",
                        "revenueChange": 12,
                        "size": 2597
                    },
                    {
                        "id": 415,
                        "name": "Keeling PLC",
                        "revenueChange": 12,
                        "size": 2160
                    },
                    {
                        "id": 428,
                        "name": "Toy-Romaguera",
                        "revenueChange": 12,
                        "size": 1277
                    },
                    {
                        "id": 487,
                        "name": "Mitchell-Jacobs",
                        "revenueChange": 12,
                        "size": 2139
                    },
                    {
                        "id": 232,
                        "name": "Beier, Green and Kunze",
                        "revenueChange": 12,
                        "size": 2388
                    },
                    {
                        "id": 30,
                        "name": "Kutch-Fadel",
                        "revenueChange": 13,
                        "size": 928
                    },
                    {
                        "id": 286,
                        "name": "DuBuque, Kirlin and Cartwright",
                        "revenueChange": 13,
                        "size": 2075
                    },
                    {
                        "id": 312,
                        "name": "Prosacco PLC",
                        "revenueChange": 13,
                        "size": 96
                    },
                    {
                        "id": 340,
                        "name": "Krajcik, Harris and Considine",
                        "revenueChange": 13,
                        "size": 754
                    },
                    {
                        "id": 122,
                        "name": "Jacobs Group",
                        "revenueChange": 13,
                        "size": 2209
                    },
                    {
                        "id": 125,
                        "name": "Greenholt PLC",
                        "revenueChange": 13,
                        "size": 1432
                    },
                    {
                        "id": 140,
                        "name": "Gutkowski, Berge and Erdman",
                        "revenueChange": 13,
                        "size": 510
                    },
                    {
                        "id": 402,
                        "name": "Aufderhar Inc",
                        "revenueChange": 13,
                        "size": 1388
                    },
                    {
                        "id": 154,
                        "name": "Rohan, Little and Cruickshank",
                        "revenueChange": 13,
                        "size": 1192
                    },
                    {
                        "id": 422,
                        "name": "Hane and Sons",
                        "revenueChange": 13,
                        "size": 371
                    },
                    {
                        "id": 429,
                        "name": "Gerhold and Sons",
                        "revenueChange": 13,
                        "size": 2573
                    },
                    {
                        "id": 438,
                        "name": "Stoltenberg, Champlin and Botsford",
                        "revenueChange": 13,
                        "size": 1321
                    },
                    {
                        "id": 449,
                        "name": "Rice-Bernier",
                        "revenueChange": 13,
                        "size": 1407
                    },
                    {
                        "id": 222,
                        "name": "Farrell, Reichel and Carter",
                        "revenueChange": 13,
                        "size": 1115
                    },
                    {
                        "id": 242,
                        "name": "Dooley Group",
                        "revenueChange": 13,
                        "size": 1873
                    },
                    {
                        "id": 260,
                        "name": "Treutel-Moore",
                        "revenueChange": 14,
                        "size": 1620
                    },
                    {
                        "id": 281,
                        "name": "Bernier, Kulas and Eichmann",
                        "revenueChange": 14,
                        "size": 394
                    },
                    {
                        "id": 67,
                        "name": "Green-Kuhlman",
                        "revenueChange": 14,
                        "size": 2131
                    },
                    {
                        "id": 135,
                        "name": "Romaguera-Hettinger",
                        "revenueChange": 14,
                        "size": 1562
                    },
                    {
                        "id": 147,
                        "name": "Fritsch-Baumbach",
                        "revenueChange": 14,
                        "size": 2631
                    },
                    {
                        "id": 414,
                        "name": "Mitchell, Dietrich and Wilderman",
                        "revenueChange": 14,
                        "size": 271
                    },
                    {
                        "id": 424,
                        "name": "Mills, Lakin and Abernathy",
                        "revenueChange": 14,
                        "size": 1152
                    },
                    {
                        "id": 198,
                        "name": "Grant, Donnelly and Schmidt",
                        "revenueChange": 14,
                        "size": 1941
                    },
                    {
                        "id": 468,
                        "name": "Sipes-Hartmann",
                        "revenueChange": 14,
                        "size": 366
                    },
                    {
                        "id": 215,
                        "name": "Spencer and Sons",
                        "revenueChange": 14,
                        "size": 1998
                    },
                    {
                        "id": 256,
                        "name": "Wilderman, Flatley and Koss",
                        "revenueChange": 15,
                        "size": 2627
                    },
                    {
                        "id": 308,
                        "name": "Abernathy-Parisian",
                        "revenueChange": 15,
                        "size": 1799
                    },
                    {
                        "id": 62,
                        "name": "Rolfson-Parker",
                        "revenueChange": 15,
                        "size": 243
                    },
                    {
                        "id": 327,
                        "name": "Nicolas-Schneider",
                        "revenueChange": 15,
                        "size": 1847
                    },
                    {
                        "id": 89,
                        "name": "Halvorson Ltd",
                        "revenueChange": 15,
                        "size": 1286
                    },
                    {
                        "id": 93,
                        "name": "Wuckert-Herman",
                        "revenueChange": 15,
                        "size": 807
                    },
                    {
                        "id": 358,
                        "name": "Collins PLC",
                        "revenueChange": 15,
                        "size": 907
                    },
                    {
                        "id": 104,
                        "name": "Mraz, Sipes and Hackett",
                        "revenueChange": 15,
                        "size": 2828
                    },
                    {
                        "id": 151,
                        "name": "O'Connell-Baumbach",
                        "revenueChange": 15,
                        "size": 1519
                    },
                    {
                        "id": 174,
                        "name": "McCullough LLC",
                        "revenueChange": 15,
                        "size": 959
                    },
                    {
                        "id": 191,
                        "name": "Nitzsche Group",
                        "revenueChange": 15,
                        "size": 2212
                    },
                    {
                        "id": 451,
                        "name": "Parisian-Cormier",
                        "revenueChange": 15,
                        "size": 2237
                    },
                    {
                        "id": 237,
                        "name": "Graham Inc",
                        "revenueChange": 15,
                        "size": 2939
                    },
                    {
                        "id": 493,
                        "name": "Ullrich Inc",
                        "revenueChange": 15,
                        "size": 321
                    },
                    {
                        "id": 300,
                        "name": "Kihn, Nicolas and Cruickshank",
                        "revenueChange": 16,
                        "size": 1409
                    },
                    {
                        "id": 45,
                        "name": "Davis Group",
                        "revenueChange": 16,
                        "size": 1946
                    },
                    {
                        "id": 338,
                        "name": "Schulist PLC",
                        "revenueChange": 16,
                        "size": 415
                    },
                    {
                        "id": 83,
                        "name": "Howell-Gusikowski",
                        "revenueChange": 16,
                        "size": 1872
                    },
                    {
                        "id": 348,
                        "name": "Heidenreich Group",
                        "revenueChange": 16,
                        "size": 2505
                    },
                    {
                        "id": 382,
                        "name": "Donnelly-Lubowitz",
                        "revenueChange": 16,
                        "size": 385
                    },
                    {
                        "id": 142,
                        "name": "Langosh-Steuber",
                        "revenueChange": 16,
                        "size": 1882
                    },
                    {
                        "id": 179,
                        "name": "Mraz-Fisher",
                        "revenueChange": 16,
                        "size": 2752
                    },
                    {
                        "id": 202,
                        "name": "Pacocha Group",
                        "revenueChange": 16,
                        "size": 1691
                    },
                    {
                        "id": 274,
                        "name": "Jerde Group",
                        "revenueChange": 17,
                        "size": 370
                    },
                    {
                        "id": 74,
                        "name": "Hettinger, Anderson and Schimmel",
                        "revenueChange": 17,
                        "size": 1814
                    },
                    {
                        "id": 397,
                        "name": "Fahey-Corkery",
                        "revenueChange": 17,
                        "size": 1407
                    },
                    {
                        "id": 409,
                        "name": "Kertzmann, Goyette and Towne",
                        "revenueChange": 17,
                        "size": 2834
                    },
                    {
                        "id": 456,
                        "name": "Ratke-Thompson",
                        "revenueChange": 17,
                        "size": 1177
                    },
                    {
                        "id": 342,
                        "name": "Herzog-Bergnaum",
                        "revenueChange": 18,
                        "size": 1310
                    },
                    {
                        "id": 354,
                        "name": "Marvin-Douglas",
                        "revenueChange": 18,
                        "size": 2996
                    },
                    {
                        "id": 117,
                        "name": "Wyman, Haag and Lehner",
                        "revenueChange": 18,
                        "size": 2502
                    },
                    {
                        "id": 389,
                        "name": "Crona, Ritchie and Borer",
                        "revenueChange": 18,
                        "size": 2817
                    },
                    {
                        "id": 400,
                        "name": "Hegmann Inc",
                        "revenueChange": 18,
                        "size": 816
                    },
                    {
                        "id": 434,
                        "name": "Bogan and Sons",
                        "revenueChange": 18,
                        "size": 914
                    },
                    {
                        "id": 345,
                        "name": "Konopelski PLC",
                        "revenueChange": 19,
                        "size": 890
                    },
                    {
                        "id": 366,
                        "name": "Senger, Glover and Keebler",
                        "revenueChange": 19,
                        "size": 583
                    },
                    {
                        "id": 431,
                        "name": "Kub-O'Hara",
                        "revenueChange": 19,
                        "size": 2516
                    }
                ]
            }
        },
        methods: {
            getAddLink(name) {
                return this.addUserLink + '?company='+  name;
            },
            getGoogleLink(name) {
                return "http://www.google.com/search?q=" + name;
            }
        },
        computed: {
            filteredClients() {
                return this.clients.filter(x => x.size >= this.sizeFrom).filter(x => x.size <= this.sizeTo).filter(x => x.revenueChange >= this.revenueFrom).filter(x => x.revenueChange <= this.revenueTo).sort((a, b) => b.revenueChange - a.revenueChange);
            },
            slicedClients() {
                return this.filteredClients.slice(0, this.listSize);
            }
        }
    }
</script>
