use efof_i16lazmat;

delete from riservazione;
delete from camera;
delete from alloggio;
delete from tipologia;
delete from amministratore_gerente;
delete from amministratore;

insert into tipologia values ("Albergo");
insert into tipologia values ("Bed & Breakfast");
insert into tipologia values ("Camping");

insert into utente values ("lazza.yt@gmail.com", "Mattia", "Lazzaroni", "$2y$10$UwB.V5xhQAxhXRKZycFydeTtbs9j9AlSSvyWO0ZPla5vwTgTtyOqC", "+41764650110", "d09bf111dee33a7a4c960ccbf68ba0f2", 1);

insert into amministratore_gerente values ("mattia.lazza@gmail.com", "Mattia", "Lazzaroni", "$2y$10$UwB.V5xhQAxhXRKZycFydeTtbs9j9AlSSvyWO0ZPla5vwTgTtyOqC", "+41764650110");

insert into amministratore values ("mattia.lazzaroni@samtrevano.ch", "$2y$10$UwB.V5xhQAxhXRKZycFydeTtbs9j9AlSSvyWO0ZPla5vwTgTtyOqC", "Mattia", "Lazzaroni");

insert into alloggio values (1,
 "Hotel Fergus Geminis",
 "Carrer dels Tamarells, s/n, 07600 Palma, Illes Balears, Spain",
 "https://www.fergushotels.com/backoffice/images/1405-building-andpool.jpg",
 "https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d12312.84870931211!2d2.746545691530753!3d39.50970067329925!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x12979646de824e6f%3A0x15a3cd304528818c!2sFERGUS%20G%C3%A9minis!5e0!3m2!1sen!2sch!4v1574775946926!5m2!1sen!2sch",
 "Isole Baleari",
 "Palma di Maiorca",
 (select email from amministratore_gerente where email = "mattia.lazza@gmail.com"),
 (select nome from tipologia where nome = "Albergo")
);
insert into alloggio values (2,
 "Alla Galleria B&B",
 "Via Antonio Cantore, 4, 37121 Verona VR, Italy",
 "http://alla-galleria-bb.verona-hotel.org/data/Photos/767x460/5377/537700/537700563.JPEG",
 "https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d11197.717568809943!2d10.985518094794717!3d45.44100266724605!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x477f5f4f52ec9c8f%3A0xa6f681f3cbce6a8e!2sAlla%20Galleria%20B%26B!5e0!3m2!1sen!2sch!4v1574776028949!5m2!1sen!2sch",
 "Veneto",
 "Verona",
 (select email from amministratore_gerente where email = "mattia.lazza@gmail.com"),
 (select nome from tipologia where nome = "Bed & Breakfast")
);
insert into alloggio values (3,
 "Keswick Camping and Caravanning Club Site",
 "Crow Park Rd, Keswick CA12 5EP, UK",
 "https://mb.cision.com/Public/6265/9664585/b695f780fd664e45_800x800ar.jpg",
 "https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d9244.756801161062!2d-3.1584935994914534!3d54.600659485721096!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x487cdb6fcc4d53ad%3A0xc7f1f99c30fff9c!2sKeswick%20Camping%20and%20Caravanning%20Club!5e0!3m2!1sen!2sch!4v1574776115035!5m2!1sen!2sch",
 "North West",
 "Keswick",
 (select email from amministratore_gerente where email = "mattia.lazza@gmail.com"),
 (select nome from tipologia where nome = "Camping")
);
insert into alloggio values (4,
 "The Temple House",
 "No. 81 Bitieshi Street, Jinjiang District, Chengdu, China 610021",
 "https://cdn-image.travelandleisure.com/sites/default/files/1558447972/temple-house-chengdu-china-96-BESTHOTELSWB19.jpg",
 "https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d13729.468550380305!2d104.07610188738288!3d30.651788608081105!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x36efc54243249f9f%3A0x11ae9ad31a4f173f!2sThe%20Temple%20House!5e0!3m2!1sen!2sch!4v1574776172687!5m2!1sen!2sch",
 "Sichuan",
 "Chengdu",
 (select email from amministratore_gerente where email = "mattia.lazza@gmail.com"),
 (select nome from tipologia where nome = "Albergo")
);
insert into alloggio values (5,
 "Borgo Egnazia",
 "Strada Comunale Egnazia, 72015 Savelletri, Fasano BR, Italy",
 "https://cdn-image.travelandleisure.com/sites/default/files/1558447972/borgo-egnazia-italy-98a-BESTHOTELSWB19.jpg",
 "https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d12067.38393252666!2d17.3876257922493!3d40.87525407064998!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x13464c83a65ac055%3A0x8eb58c8e074de773!2sBorgo%20Egnazia!5e0!3m2!1sen!2sch!4v1574776262087!5m2!1sen!2sch",
 "Puglia",
 "Savelletri",
 (select email from amministratore_gerente where email = "mattia.lazza@gmail.com"),
 (select nome from tipologia where nome = "Albergo")
);
insert into alloggio values (6,
 "Belmond La Résidence Phou Vao",
 "Lao PDR, Luang Prabang 84330, Laos",
 "https://cdn-image.travelandleisure.com/sites/default/files/1558447972/belmond-la-residence-phou-vao-laos-91-BESTHOTELSWB19.jpg",
 "https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15008.470993173947!2d102.1279250836366!3d19.877249287178312!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x312ed5fefa3af579%3A0x9a97c9aaeac92f5c!2sBelmond%20La%20R%C3%A9sidence%20Phou%20Vao!5e0!3m2!1sen!2sch!4v1574776300670!5m2!1sen!2sch",
 "Luang Prabang",
 "Luang Prabang",
 (select email from amministratore_gerente where email = "mattia.lazza@gmail.com"),
 (select nome from tipologia where nome = "Albergo")
);
insert into alloggio values (7,
 "Le Meurice",
 "228 Rue de Rivoli, 75001 Paris, France",
 "https://cdn-image.travelandleisure.com/sites/default/files/le-meurice-paris-france-replacement-89-besthotelswb19.jpg",
 "https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d10498.505995318033!2d2.3194040968407714!3d48.86533227023143!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47e66e2ddeee95a5%3A0x10fd58441fc5a059!2sLe%20Meurice!5e0!3m2!1sen!2sch!4v1574776462009!5m2!1sen!2sch",
 "Île-de-France",
 "Paris",
 (select email from amministratore_gerente where email = "mattia.lazza@gmail.com"),
 (select nome from tipologia where nome = "Albergo")
);
insert into alloggio values (8,
 "Post Ranch Inn",
 "47900 CA-1, Big Sur, CA 93920, United States",
 "https://cdn-image.travelandleisure.com/sites/default/files/1558447352/post-ranch-inn-california-84-BESTHOTELSWB19.jpg",
 "https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d12873.52095699266!2d-121.78054511011072!3d36.23025618267802!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8092bc63c40e12c9%3A0x6191a2b967696e87!2sPost%20Ranch%20Inn!5e0!3m2!1sen!2sch!4v1574776511784!5m2!1sen!2sch",
 "California",
 "Big Sur",
 (select email from amministratore_gerente where email = "mattia.lazza@gmail.com"),
 (select nome from tipologia where nome = "Albergo")
);
insert into alloggio values (9,
 "Triple Creek Ranch",
 "5551 W Fork Rd, Darby, MT 59829, United States",
 "https://cdn-image.travelandleisure.com/sites/default/files/1558447352/triple-creek-ranch-montana-79-BESTHOTELSWB19.jpg",
 "https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d11110.64855292681!2d-114.20287820495042!3d45.87806876736297!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x535924cf38ff60bf%3A0xc8497ee5ebcfe4!2sTriple%20Creek%20Ranch%2C%20A%20Montana%20Hideaway!5e0!3m2!1sen!2sch!4v1574776571238!5m2!1sen!2sch",
 "Montana",
 "Darby",
 (select email from amministratore_gerente where email = "mattia.lazza@gmail.com"),
 (select nome from tipologia where nome = "Albergo")
);
insert into alloggio values (10,
 "Katikies Hotel",
 "Nik. Nomikou (Main Street), Oía 847 02, Greece",
 "https://cdn-image.travelandleisure.com/sites/default/files/1558447352/katikies-hotel-greece-78-BESTHOTELSWB19.jpg",
 "https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d12835.268973639051!2d25.373797690001293!3d36.4619726818772!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x1499cc809c7cf365%3A0x7cad8e86fb244ec4!2sKatikies%20Hotel!5e0!3m2!1sen!2sch!4v1574776609581!5m2!1sen!2sch",
 "South Aegean",
 "Oia",
 (select email from amministratore_gerente where email = "mattia.lazza@gmail.com"),
 (select nome from tipologia where nome = "Albergo")
);
insert into alloggio values (11,
 "Carson Ridge Luxury Cabins",
 "1261 Wind River Hwy, Carson, WA 98610, United States",
 "https://www.bedandbreakfast.com/files/live/sites/bnbus/files/shared/Inns/luxurious/1170x450_carson-ridge-luxury-cabins.jpg",
 "https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d11140.285087890741!2d-121.82950280503721!3d45.72966546731458!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x5495dfc6ac627e65%3A0xc0e5d2f7cfece285!2sCarson%20Ridge%20Luxury%20Cabins!5e0!3m2!1sen!2sch!4v1574776653495!5m2!1sen!2sch",
 "Washington",
 "Carson",
 (select email from amministratore_gerente where email = "mattia.lazza@gmail.com"),
 (select nome from tipologia where nome = "Bed & Breakfast")
);
insert into alloggio values (12,
 "Chestnut Hill Bed & Breakfast",
 "236 Caroline St, Orange, VA 22960, United States",
 "https://www.bedandbreakfast.com/files/live/sites/bnbus/files/shared/Inns/luxurious/1170x450_chestnut-hill.jpg",
 "https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d12534.971389937346!2d-78.12283880911954!3d38.23907407643112!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89b41075afa102fd%3A0xb55faae38ca2d481!2sChestnut%20Hill%20Bed%20%26%20Breakfast!5e0!3m2!1sen!2sch!4v1574776708643!5m2!1sen!2sch",
 "Virginia",
 "Orange",
 (select email from amministratore_gerente where email = "mattia.lazza@gmail.com"),
 (select nome from tipologia where nome = "Bed & Breakfast")
);
insert into alloggio values (13,
 "Old Monterey Inn",
 "500 Martin St, Monterey, CA 93940, United States",
 "https://www.bedandbreakfast.com/files/live/sites/bnbus/files/shared/Inns/luxurious/1170x450_old-monterey.jpg",
 "https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d12813.45520916341!2d-121.90928650993484!3d36.59354618143176!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x808de427988f3d05%3A0x5fd3b78be4b4b530!2sOld%20Monterey%20Inn!5e0!3m2!1sen!2sch!4v1574776752542!5m2!1sen!2sch",
 "California",
 "Monterey",
 (select email from amministratore_gerente where email = "mattia.lazza@gmail.com"),
 (select nome from tipologia where nome = "Bed & Breakfast")
);
insert into alloggio values (14,
 "Port d'Hiver Bed and Breakfast",
 "201 Ocean Ave, Melbourne Beach, FL 32951, United States",
 "https://www.bedandbreakfast.com/files/live/sites/bnbus/files/shared/Inns/luxurious/1170x450_port-dhiver.jpg",
 "https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d14082.293693036065!2d-80.56637731365043!3d28.068046723660018!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x88de115862eb45fb%3A0xf5bddbe28e7ad24!2sPort%20d&#39;Hiver%20Bed%20and%20Breakfast!5e0!3m2!1sen!2sch!4v1574776823535!5m2!1sen!2sch",
 "Florida",
 "Melbourne",
 (select email from amministratore_gerente where email = "mattia.lazza@gmail.com"),
 (select nome from tipologia where nome = "Bed & Breakfast")
);
insert into alloggio values (15,
 "Camping La Pointe",
 "Camping La Pointe route de Saint Coulitz, 29150 Chateaulin, France",
 "https://i.guim.co.uk/img/media/569fa7ab4ab731d3330e0ab40415478a2256561c/5_0_2010_1206/master/2010.jpg?width=620&quality=45&auto=format&fit=max&dpr=2&s=52447bfcc60b2e02bef61e38e8512a3d",
 "https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d10639.967016169407!2d-4.093783403573129!3d48.187510369264636!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x481131b5b3370509%3A0xce28340c0d757dd!2sCamping%20La%20Pointe!5e0!3m2!1sen!2sch!4v1574776892277!5m2!1sen!2sch",
 "Brittany",
 "Chateaulin",
 (select email from amministratore_gerente where email = "mattia.lazza@gmail.com"),
 (select nome from tipologia where nome = "Camping")
);
insert into alloggio values (16,
 "D’Olde Kamp",
 "Dwingelerweg 26, 7964 KK Ansen, Netherlands",
 "https://i.guim.co.uk/img/media/a9938ee548da93e7230c443c11b5f75557aed6f7/0_219_2048_1229/master/2048.jpg?width=620&quality=45&auto=format&fit=max&dpr=2&s=cddc6e6934d8cec1b56f4b25e1fa964f",
 "https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d9651.025384704923!2d6.330395599320315!3d52.7907015794373!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47c813f57c7ea37b%3A0x70d36ec012a789c8!2zZOKAmU9sZGUgS2FtcA!5e0!3m2!1sen!2sch!4v1574776941618!5m2!1sen!2sch",
 "Drenthe",
 "Ansen",
 (select email from amministratore_gerente where email = "mattia.lazza@gmail.com"),
 (select nome from tipologia where nome = "Camping")
);
insert into alloggio values (17,
 "Camping Lindenhof",
 "Mörigenweg 2, 2572 Sutz-Lattrigen",
 "https://i.guim.co.uk/img/media/ca9e503799d408706e15f0130ad29065a136ba27/0_192_2560_1536/master/2560.jpg?width=620&quality=45&auto=format&fit=max&dpr=2&s=a8bd56d5fc4445f2b331d49949c9df27",
 "https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d10865.216145834122!2d7.201608595767769!3d47.09311006809459!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x478e1a250f87d833%3A0x881fdeab609c1e3b!2sCamping%20Lindenhof!5e0!3m2!1sen!2sch!4v1574776978683!5m2!1sen!2sch",
 "Bern",
 "Sutz-Lattrigen",
 (select email from amministratore_gerente where email = "mattia.lazza@gmail.com"),
 (select nome from tipologia where nome = "Camping")
);
insert into alloggio values (18,
 "Camping agritourism Karst",
 "Località Aurisina Cave, 55, 34011 Duino-Aurisina TS, Italy",
 "https://i.guim.co.uk/img/media/848f5804f0a85df0e2abc3433f0fa86deb08b315/0_97_960_576/master/960.jpg?width=620&quality=45&auto=format&fit=max&dpr=2&s=3216794549f1237cd02e26bfad1dab6a",
 "https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d11133.870842305396!2d13.643736494981582!3d45.761816167324326!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x477b0c32cb6e3e6f%3A0x39fb94d96fb16e56!2sCamping%20agritourism%20Karst!5e0!3m2!1sen!2sch!4v1574777043184!5m2!1sen!2sch",
 "Friuli-Venezia Giulia",
 "Duino-Aurisina",
 (select email from amministratore_gerente where email = "mattia.lazza@gmail.com"),
 (select nome from tipologia where nome = "Camping")
);
insert into alloggio values (19,
 "Camp Vala",
 "20250, Postup, Croatia",
 "https://i.guim.co.uk/img/media/d2bbebf188b02f7f58076e1ce9b466660fd42557/25_0_750_450/master/750.jpg?width=620&quality=45&auto=format&fit=max&dpr=2&s=515dc49040d38b1d47a47bc88a30cba0",
 "https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d11676.34148348576!2d17.21738799339392!3d42.976475268036396!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x134a53b3eddeab1f%3A0x1a71c4721bf9a0f7!2sCamp%20%22Vala%22!5e0!3m2!1sen!2sch!4v1574777085945!5m2!1sen!2sch",
 "Postup",
 "Mokalo",
 (select email from amministratore_gerente where email = "mattia.lazza@gmail.com"),
 (select nome from tipologia where nome = "Camping")
);
insert into alloggio values (20,
 "Camping Camino de Santiago",
 "Camping Camino de Santiago, 09110 Castrojeriz, Burgos, Spain",
 "https://i.guim.co.uk/img/media/9eb333ef82b241edae906dcf407f232a02c2e679/0_126_5424_3255/master/5424.jpg?width=620&quality=45&auto=format&fit=max&dpr=2&s=af277c60be60b9fe01ec315e0a840271",
 "https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d11805.671875563556!2d-4.140592406984631!3d42.290946168693374!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd463c8494269423%3A0x573e24ca6853a96c!2sCamping%20Camino%20De%20Santiago!5e0!3m2!1sen!2sch!4v1574777156613!5m2!1sen!2sch",
 "Castile and Leon",
 "Burgos",
 (select email from amministratore_gerente where email = "mattia.lazza@gmail.com"),
 (select nome from tipologia where nome = "Camping")
);


insert into camera values (1, 0, 2, 1, "mattia.lazza@gmail.com");
insert into camera values (2, 0, 2, 2, "mattia.lazza@gmail.com");
insert into camera values (3, 0, 2, 3, "mattia.lazza@gmail.com");
insert into camera values (4, 0, 2, 4, "mattia.lazza@gmail.com");
insert into camera values (5, 0, 2, 5, "mattia.lazza@gmail.com");
insert into camera values (6, 0, 2, 6, "mattia.lazza@gmail.com");
insert into camera values (7, 0, 2, 7, "mattia.lazza@gmail.com");
insert into camera values (8, 0, 2, 8, "mattia.lazza@gmail.com");
insert into camera values (9, 0, 2, 9, "mattia.lazza@gmail.com");
insert into camera values (10, 0, 2, 10, "mattia.lazza@gmail.com");
insert into camera values (11, 0, 2, 11, "mattia.lazza@gmail.com");
insert into camera values (12, 0, 2, 12, "mattia.lazza@gmail.com");
insert into camera values (13, 0, 2, 13, "mattia.lazza@gmail.com");
insert into camera values (14, 0, 2, 14, "mattia.lazza@gmail.com");
insert into camera values (15, 0, 2, 15, "mattia.lazza@gmail.com");
insert into camera values (16, 0, 2, 16, "mattia.lazza@gmail.com");
insert into camera values (17, 0, 2, 17, "mattia.lazza@gmail.com");
insert into camera values (18, 0, 2, 18, "mattia.lazza@gmail.com");
insert into camera values (19, 0, 2, 19, "mattia.lazza@gmail.com");
insert into camera values (20, 0, 2, 20, "mattia.lazza@gmail.com");

insert into camera values (21, 1, 1, 1, "mattia.lazza@gmail.com");
insert into camera values (22, 0, 1, 1, "mattia.lazza@gmail.com");
insert into camera values (23, 1, 2, 1, "mattia.lazza@gmail.com");
insert into camera values (24, 2, 2, 1, "mattia.lazza@gmail.com");
insert into camera values (25, 3, 2, 1, "mattia.lazza@gmail.com");

insert into camera values (26, 1, 1, 2, "mattia.lazza@gmail.com");
insert into camera values (27, 0, 1, 2, "mattia.lazza@gmail.com");
insert into camera values (28, 1, 2, 2, "mattia.lazza@gmail.com");
insert into camera values (29, 2, 2, 2, "mattia.lazza@gmail.com");
insert into camera values (30, 3, 2, 2, "mattia.lazza@gmail.com");

insert into camera values (31, 1, 1, 3, "mattia.lazza@gmail.com");
insert into camera values (32, 0, 1, 3, "mattia.lazza@gmail.com");
insert into camera values (33, 1, 2, 3, "mattia.lazza@gmail.com");
insert into camera values (34, 2, 2, 3, "mattia.lazza@gmail.com");
insert into camera values (35, 3, 2, 3, "mattia.lazza@gmail.com");

insert into riservazione values (1, '2019-10-05', '2019-10-10', 1, 'lazza.yt@gmail.com');
insert into riservazione values (2, '2019-10-05', '2019-10-10', 2, 'lazza.yt@gmail.com');
insert into riservazione values (3, '2019-10-05', '2019-10-10', 3, 'lazza.yt@gmail.com');
insert into riservazione values (4, '2019-10-05', '2019-10-10', 4, 'lazza.yt@gmail.com');
insert into riservazione values (5, '2019-10-05', '2019-10-10', 5, 'lazza.yt@gmail.com');
insert into riservazione values (6, '2019-10-05', '2019-10-10', 6, 'lazza.yt@gmail.com');
insert into riservazione values (7, '2019-10-05', '2019-10-10', 7, 'lazza.yt@gmail.com');
insert into riservazione values (8, '2019-10-05', '2019-10-10', 8, 'lazza.yt@gmail.com');
insert into riservazione values (9, '2019-10-05', '2019-10-10', 9, 'lazza.yt@gmail.com');
insert into riservazione values (10, '2019-10-05', '2019-10-10', 10, 'lazza.yt@gmail.com');
insert into riservazione values (11, '2019-10-05', '2019-10-10', 11, 'lazza.yt@gmail.com');
insert into riservazione values (12, '2019-10-05', '2019-10-10', 12, 'lazza.yt@gmail.com');
insert into riservazione values (13, '2019-10-05', '2019-10-10', 13, 'lazza.yt@gmail.com');
insert into riservazione values (14, '2019-10-05', '2019-10-10', 14, 'lazza.yt@gmail.com');
insert into riservazione values (15, '2019-10-05', '2019-10-10', 15, 'lazza.yt@gmail.com');
insert into riservazione values (16, '2019-10-05', '2019-10-10', 16, 'lazza.yt@gmail.com');
insert into riservazione values (17, '2019-10-05', '2019-10-10', 17, 'lazza.yt@gmail.com');
insert into riservazione values (18, '2019-10-05', '2019-10-10', 18, 'lazza.yt@gmail.com');
insert into riservazione values (19, '2019-10-05', '2019-10-10', 19, 'lazza.yt@gmail.com');
insert into riservazione values (20, '2019-10-05', '2019-10-10', 20, 'lazza.yt@gmail.com');
insert into riservazione values (21, '2019-11-25', '2019-12-18', 1, 'lazza.yt@gmail.com');
insert into riservazione values (22, '2019-11-15', '2019-11-20', 1, 'lazza.yt@gmail.com');
insert into riservazione values (23, '2019-12-16', '2019-12-22', 1, 'lazza.yt@gmail.com');
insert into riservazione values (24, '2019-12-29', '2020-01-05', 1, 'lazza.yt@gmail.com');




