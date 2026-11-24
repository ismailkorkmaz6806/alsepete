<?php

namespace App\Services;

class LocationService
{
    /**
     * Türkiye'nin 81 ili ve ilçeleri.
     */
    public static function getCitiesWithDistricts(): array
    {
        return [
            'Adana' => ['Seyhan', 'Çukurova', 'Yüreğir', 'Sarıçam', 'Ceyhan', 'Kozan', 'İmamoğlu', 'Karaisalı', 'Karataş', 'Pozantı'],
            'Adıyaman' => ['Merkez', 'Besni', 'Gölbaşı', 'Kahta', 'Gerger', 'Samsat', 'Sincik', 'Tut', 'Çelikhan'],
            'Afyonkarahisar' => ['Merkez', 'Sandıklı', 'Dinar', 'Bolvadin', 'Emirdağ', 'Şuhut', 'Çay', 'İhsaniye', 'İscehisar'],
            'Ağrı' => ['Merkez', 'Doğubayazıt', 'Patnos', 'Diyadin', 'Eleşkirt', 'Tutak', 'Hamur', 'Taşlıçay'],
            'Amasya' => ['Merkez', 'Merzifon', 'Suluova', 'Taşova', 'Gümüşhacıköy', 'Göynücek', 'Hamamözü'],
            'Ankara' => ['Çankaya', 'Keçiören', 'Yenimahalle', 'Mamak', 'Etimesgut', 'Sincan', 'Altındağ', 'Pursaklar', 'Gölbaşı', 'Polatlı', 'Kahramankazan'],
            'Antalya' => ['Muratpaşa', 'Kepez', 'Konyaaltı', 'Alanya', 'Manavgat', 'Serik', 'Kumluca', 'Kaş', 'Korkuteli', 'Gazipaşa', 'Finike', 'Kemer'],
            'Artvin' => ['Merkez', 'Hopa', 'Borçka', 'Arhavi', 'Yusufeli', 'Şavşat', 'Ardanuç', 'Murgul', 'Kemalpaşa'],
            'Aydın' => ['Efeler', 'Kuşadası', 'Söke', 'Nazilli', 'Didim', 'İncirliova', 'Çine', 'Germencik', 'Bozdoğan', 'Köşk'],
            'Balıkesir' => ['Karesi', 'Altıeylül', 'Bandırma', 'Edremit', 'Gönen', 'Ayvalık', 'Burhaniye', 'Bigadiç', 'Susurluk', 'Erdek'],
            'Bilecik' => ['Merkez', 'Bozüyük', 'Osmaneli', 'Söğüt', 'Gölpazarı', 'Pazaryeri', 'Yenipazar', 'İnhisar'],
            'Bingöl' => ['Merkez', 'Genç', 'Solhan', 'Karlıova', 'Adaklı', 'Kiğı', 'Yedisu', 'Yayladere'],
            'Bitlis' => ['Tatvan', 'Merkez', 'Güroymak', 'Ahlat', 'Hizan', 'Mutki', 'Adilcevaz'],
            'Bolu' => ['Merkez', 'Gerede', 'Mudurnu', 'Göynük', 'Mengen', 'Yeniçağa', 'Dörtdivan', 'Seben', 'Kıbrıscık'],
            'Burdur' => ['Merkez', 'Bucak', 'Gölhisar', 'Yeşilova', 'Ağlasun', 'Çavdır', 'Tefenni', 'Karamanlı', 'Altınyayla'],
            'Bursa' => ['Osmangazi', 'Nilüfer', 'Yıldırım', 'İnegöl', 'Gemlik', 'Mustafakemalpaşa', 'Mudanya', 'Gürsu', 'Karacabey', 'Orhangazi', 'Kestel'],
            'Çanakkale' => ['Merkez', 'Biga', 'Çan', 'Gelibolu', 'Yenice', 'Ayvacık', 'Ezine', 'Bayramiç', 'Lapseki', 'Eceabat', 'Bozcaada', 'Gökçeada'],
            'Çankırı' => ['Merkez', 'Çerkeş', 'Ilgaz', 'Orta', 'Şabanözü', 'Kurşunlu', 'Yapraklı', 'Kızılırmak', 'Eldivan', 'Atkaracalar'],
            'Çorum' => ['Merkez', 'Sungurlu', 'Osmancık', 'İskilip', 'Alaca', 'Bayat', 'Mecitözü', 'Kargı', 'Ortaköy', 'Uğurludağ'],
            'Denizli' => ['Pamukkale', 'Merkezefendi', 'Çivril', 'Acıpayam', 'Tavas', 'Honaz', 'Sarayköy', 'Buldan', 'Kale', 'Çal', 'Çameli'],
            'Diyarbakır' => ['Bağlar', 'Kayapınar', 'Yenişehir', 'Sur', 'Ergani', 'Bismil', 'Silvan', 'Çınar', 'Çermik', 'Dicle', 'Kulp'],
            'Edirne' => ['Merkez', 'Keşan', 'Uzunköprü', 'İpsala', 'Havsa', 'Meriç', 'Enez', 'Süloğlu', 'Lalapaşa'],
            'Elazığ' => ['Merkez', 'Kovancılar', 'Karakoçan', 'Palu', 'Arıcak', 'Baskil', 'Maden', 'Sivrice', 'Alacakaya', 'Keban', 'Ağın'],
            'Erzincan' => ['Merkez', 'Tercan', 'Üzümlü', 'Çayırlı', 'İliç', 'Kemah', 'Kemaliye', 'Otlukbeli', 'Refahiye'],
            'Erzurum' => ['Yakutiye', 'Palandöken', 'Aziziye', 'Horasan', 'Oltu', 'Pasinler', 'Karayazı', 'Hınıs', 'Tekman', 'Aşkale'],
            'Eskişehir' => ['Odunpazarı', 'Tepebaşı', 'Sivrihisar', 'Çifteler', 'Seyitgazi', 'Alpu', 'Mihalıççık', 'Mahmudiye', 'Beylikova', 'İnönü'],
            'Gaziantep' => ['Şahinbey', 'Şehitkamil', 'Nizip', 'İslahiye', 'Nurdağı', 'Araban', 'Oğuzeli', 'Yavuzeli', 'Karkamış'],
            'Giresun' => ['Merkez', 'Bulancak', 'Espiye', 'Görele', 'Tirebolu', 'Dereli', 'Şebinkarahisar', 'Keşap', 'Yağlıdere', 'Piraziz'],
            'Gümüşhane' => ['Merkez', 'Kelkit', 'Şiran', 'Kürtün', 'Torul', 'Köse'],
            'Hakkari' => ['Yüksekova', 'Merkez', 'Şemdinli', 'Çukurca', 'Derecik'],
            'Hatay' => ['Antakya', 'İskenderun', 'Defne', 'Dörtyol', 'Samandağ', 'Kırıkhan', 'Reyhanlı', 'Arsuz', 'Altınözü', 'Hassa', 'Payas', 'Erzin'],
            'Isparta' => ['Merkez', 'Yalvaç', 'Eğirdir', 'Şarkikaraağaç', 'Gelendost', 'Keçiborlu', 'Senirkent', 'Sütçüler', 'Gönen', 'Uluborlu'],
            'Mersin' => ['Tarsus', 'Toroslar', 'Akdeniz', 'Yenişehir', 'Mezitli', 'Erdemli', 'Silifke', 'Anamur', 'Mut', 'Bozyazı', 'Gülnar', 'Aydıncık'],
            'İstanbul' => ['Kadıköy', 'Beşiktaş', 'Üsküdar', 'Şişli', 'Bakırköy', 'Fatih', 'Maltepe', 'Ataşehir', 'Ümraniye', 'Beylikdüzü', 'Pendik', 'Sarıyer', 'Kartal', 'Zeytinburnu', 'Bahçelievler', 'Küçükçekmece', 'Başakşehir', 'Esenyurt'],
            'İzmir' => ['Konak', 'Karşıyaka', 'Bornova', 'Buca', 'Çiğli', 'Gaziemir', 'Bayraklı', 'Karabağlar', 'Torbalı', 'Menemen', 'Aliağa', 'Urla', 'Çeşme', 'Seferihisar', 'Foça'],
            'Kars' => ['Merkez', 'Kağızman', 'Sarıkamış', 'Selim', 'Digor', 'Arpaçay', 'Akyaka', 'Susuz'],
            'Kastamonu' => ['Merkez', 'Tosya', 'Taşköprü', 'Cide', 'İnebolu', 'Araç', 'Devrekani', 'Bozkurt', 'Daday', 'Azdavay'],
            'Kayseri' => ['Melikgazi', 'Kocasinan', 'Talas', 'Develi', 'Yahyalı', 'Bünyan', 'İncesu', 'Pınarbaşı', 'Tomarza', 'Yeşilhisar'],
            'Kırklareli' => ['Merkez', 'Lüleburgaz', 'Babaeski', 'Vize', 'Pınarhisar', 'Demirköy', 'Pehlivanköy', 'Kofçaz'],
            'Kırşehir' => ['Merkez', 'Kaman', 'Mucur', 'Çiçekdağı', 'Akpınar', 'Boztepe', 'Akçakent'],
            'Kocaeli' => ['İzmit', 'Gebze', 'Darıca', 'Körfez', 'Gölcük', 'Derince', 'Çayırova', 'Kartepe', 'Başiskele', 'Karamürsel', 'Kandıra', 'Dilovası'],
            'Konya' => ['Selçuklu', 'Meram', 'Karatay', 'Ereğli', 'Akşehir', 'Beyşehir', 'Çumra', 'Seydişehir', 'Ilgın', 'Cihanbeyli', 'Kulu'],
            'Kütahya' => ['Merkez', 'Tavşanlı', 'Simav', 'Gediz', 'Emet', 'Altıntaş', 'Domaniç', 'Hisarcık', 'Aslanapa', 'Çavdarhisar'],
            'Malatya' => ['Battalgazi', 'Yeşilyurt', 'Doğanşehir', 'Akçadağ', 'Darende', 'Hekimhan', 'Pütürge', 'Yazıhan', 'Arapgir', 'Kuluncak'],
            'Manisa' => ['Yunusemre', 'Şehzadeler', 'Akhisar', 'Turgutlu', 'Salihli', 'Soma', 'Alaşehir', 'Saruhanlı', 'Demirci', 'Kırkağaç'],
            'Kahramanmaraş' => ['Onikişubat', 'Dulkadiroğlu', 'Elbistan', 'Afşin', 'Türkoğlu', 'Pazarcık', 'Göksun', 'Andırın', 'Çağlayancerit'],
            'Mardin' => ['Artuklu', 'Kızıltepe', 'Midyat', 'Nusaybin', 'Derik', 'Mazıdağı', 'Dargeçit', 'Savur', 'Yeşilli', 'Ömerli'],
            'Muğla' => ['Bodrum', 'Fethiye', 'Marmaris', 'Menteşe', 'Milas', 'Ortaca', 'Dalaman', 'Yatağan', 'Ula', 'Köyceğiz', 'Datça', 'Seydikemer'],
            'Muş' => ['Merkez', 'Bulanık', 'Malazgirt', 'Varto', 'Hasköy', 'Korkut'],
            'Nevşehir' => ['Merkez', 'Ürgüp', 'Avanos', 'Gülşehir', 'Derinkuyu', 'Kozaklı', 'Acıgöl', 'Hacıbektaş'],
            'Niğde' => ['Merkez', 'Bor', 'Çiftlik', 'Ulukışla', 'Altunhisar', 'Çamardı'],
            'Ordu' => ['Altınordu', 'Ünye', 'Fatsa', 'Kumru', 'Korgan', 'Gölköy', 'Perşembe', 'Aybastı', 'Ulubey', 'Mesudiye', 'Akkuş'],
            'Rize' => ['Merkez', 'Çayeli', 'Ardeşen', 'Pazar', 'Fındıklı', 'Güneysu', 'Kalkandere', 'İyidere', 'Derepazarı', 'Çamlıhemşin', 'İkizdere'],
            'Sakarya' => ['Adapazarı', 'Serdivan', 'Akyazı', 'Erenler', 'Hendek', 'Karasu', 'Geyve', 'Arifiye', 'Sapanca', 'Pamukova', 'Ferizli'],
            'Samsun' => ['İlkadım', 'Atakum', 'Bafra', 'Çarşamba', 'Canik', 'Vezirköprü', 'Terme', 'Tekkeköy', 'Havza', 'Alaçam', '19 Mayıs'],
            'Siirt' => ['Merkez', 'Kurtalan', 'Pervari', 'Baykan', 'Şirvan', 'Eruh', 'Tillo'],
            'Sinop' => ['Merkez', 'Boyabat', 'Gerze', 'Ayancık', 'Durağan', 'Türkeli', 'Erfelek', 'Saraydüzü', 'Dikmen'],
            'Sivas' => ['Merkez', 'Şarkışla', 'Yıldızeli', 'Suşehri', 'Gemerek', 'Zara', 'Kangal', 'Gürün', 'Divriği', 'Koyulhisar', 'Hafik'],
            'Tekirdağ' => ['Süleymanpaşa', 'Çorlu', 'Çerkezköy', 'Kapaklı', 'Ergene', 'Malkara', 'Saray', 'Hayrabolu', 'Şarköy', 'Muratlı', 'Marmaraereğlisi'],
            'Tokat' => ['Merkez', 'Erbaa', 'Turhal', 'Niksar', 'Zile', 'Reşadiye', 'Almus', 'Pazar', 'Yeşilyurt', 'Artova', 'Sulusaray'],
            'Trabzon' => ['Ortahisar', 'Akçaabat', 'Araklı', 'Of', 'Yomra', 'Arsin', 'Vakfıkebir', 'Sürmene', 'Maçka', 'Beşikdüzü', 'Çarşıbaşı', 'Tonya'],
            'Tunceli' => ['Merkez', 'Pertek', 'Mazgirt', 'Çemişgezek', 'Hozat', 'Ovacık', 'Pülümür', 'Nazımiye'],
            'Şanlıurfa' => ['Haliliye', 'Eyyübiye', 'Karaköprü', 'Siverek', 'Viranşehir', 'Suruç', 'Birecik', 'Akçakale', 'Ceylanpınar', 'Bozova', 'Harran'],
            'Uşak' => ['Merkez', 'Banaz', 'Eşme', 'Sivaslı', 'Ulubey', 'Karahallı'],
            'Van' => ['İpekyolu', 'Tuşba', 'Edremit', 'Erciş', 'Özalp', 'Çaldıran', 'Başkale', 'Muradiye', 'Gürpınar', 'Gevaş', 'Saray'],
            'Yozgat' => ['Merkez', 'Sorgun', 'Akdağmadeni', 'Yerköy', 'Boğazlıyan', 'Sarıkaya', 'Çekerek', 'Şefaatli', 'Saraykent', 'Çayıralan'],
            'Zonguldak' => ['Merkez', 'Ereğli', 'Çaycuma', 'Devrek', 'Kozlu', 'Alaplı', 'Kilimli', 'Gökçebey'],
            'Aksaray' => ['Merkez', 'Ortaköy', 'Eskil', 'Gülağaç', 'Güzelyurt', 'Ağaçören', 'Sultanhanı', 'Sarıyahşi'],
            'Bayburt' => ['Merkez', 'Demirözü', 'Aydıntepe'],
            'Karaman' => ['Merkez', 'Ermenek', 'Sarıveliler', 'Ayrancı', 'Kazımkarabekir', 'Başyayla'],
            'Kırıkkale' => ['Merkez', 'Yahşihan', 'Keskin', 'Delice', 'Sulakyurt', 'Bahşılı', 'Balışeyh', 'Karakeçili', 'Çelebi'],
            'Batman' => ['Merkez', 'Kozluk', 'Sason', 'Beşiri', 'Gercüş', 'Hasankeyf'],
            'Şırnak' => ['Cizre', 'Silopi', 'Merkez', 'İdil', 'Uludere', 'Beytüşşebap', 'Güçlükonak'],
            'Bartın' => ['Merkez', 'Ulus', 'Amasra', 'Kurucaşile'],
            'Ardahan' => ['Merkez', 'Göle', 'Çıldır', 'Hanak', 'Posof', 'Damal'],
            'Iğdır' => ['Merkez', 'Tuzluca', 'Aralık', 'Karakoyunlu'],
            'Yalova' => ['Merkez', 'Çiftlikköy', 'Çınarcık', 'Altınova', 'Armutlu', 'Termal'],
            'Karabük' => ['Merkez', 'Safranbolu', 'Yenice', 'Eskipazar', 'Eflani', 'Ovacık'],
            'Kilis' => ['Merkez', 'Musabeyli', 'Elbeyli', 'Polateli'],
            'Osmaniye' => ['Merkez', 'Kadirli', 'Düziçi', 'Bahçe', 'Toprakkale', 'Sumbas', 'Hasanbeyli'],
            'Düzce' => ['Merkez', 'Akçakoca', 'Kaynaşlı', 'Gölyaka', 'Çilimli', 'Yığılca', 'Gümüşova', 'Cumayeri'],
        ];
    }

    public static function getCities(): array
    {
        return array_keys(self::getCitiesWithDistricts());
    }

    public static function getDistricts(string $city): array
    {
        $all = self::getCitiesWithDistricts();

        return $all[$city] ?? [];
    }
}
