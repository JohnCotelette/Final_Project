<?php

namespace App\DataFixtures;

use App\DataFixtures\BaseFixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\Persistence\ObjectManager;
use App\Service\FileService;
use App\Entity\User;
use App\Entity\Business;
use App\Entity\Avatar;
use App\Entity\Cv;

/**
 * Class UserFixture
 * @package App\DataFixtures
 */
class UserFixture extends BaseFixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    private $fileService;

    private $index = 0;

    private $business_index = 0;

    const DEFAULT_ENDMAIL = "@hotmail.fr";

    const DEFAULT_CANDIDATE = "candidate";

    const DEFAULT_RECRUITER = "recruiter";

    const CHOICES = ["1", "2", "3", "4", "5"];

    const BUSINESS_SIRET_NUMBER = [
        80042662900013,
        79101208100042,
        48763135000054,
        83816034900018,
        82281301000011,
        51445803300065,
        50882361400047,
        53311964000035,
        50951276000018,
        80532165000030,
        81827957200015,
        80369311800017,
        52245715900039,
        42868848500047,
        82819372200034,
        82128697800027,
        79875187100012,
        88608072000024,
        52993100800039,
        79017292800067,
        80019780800036,
        82789689500014,
        84322602800010,
        78944160700015,
        39150469300063,
        80130466800040,
        30613890001294,
        34873105000046,
        49215593200048,
        82427359300031,
    ];

    const BUSINESS_NAME = [
        "Externatic",
        "Dataiku",
        "HC Resources",
        "Konekt",
        "Unlck",
        "Palot IT",
        "Naitways",
        "Italent",
        "Ekino",
        "Adbi",
        "Kostango",
        "Eion",
        "Recrut-Info",
        "BETC",
        "NuxtJS",
        "LM5P",
        "Knitiv",
        "Fisa",
        "Carenity",
        "Abbeal",
        "Homerez",
        "uTip",
        "AVECsanté",
        "Keolio",
        "Aubay Grand-Ouest",
        "FAO Consulting",
        "Decathlon",
        "Emploi Systeme SA",
        "WINAMAX",
        "Wekey",
    ];

    const BUSINESS_EMPLOYEES_NUMBER = [
        "20 à 99 employés",
        "100 à 499 employés",
        "19 employés et moins",
        "19 employés et moins",
        "19 employés et moins",
        "100 à 499 employés",
        "20 à 99 employés",
        "19 employés et moins",
        "100 à 499 employés",
        "20 à 99 employés",
        "19 employés et moins",
        "19 employés et moins",
        "19 employés et moins",
        "500 employés et plus",
        "19 employés et moins",
        "19 employés et moins",
        "19 employés et moins",
        "20 à 99 employés",
        "19 employés et moins",
        "20 à 99 employés",
        "20 à 99 employés",
        "19 employés et moins",
        "19 employés et moins",
        "19 employés et moins",
        "500 employés et plus",
        "20 à 99 employés",
        "500 employés et plus",
        "20 à 99 employés",
        "100 à 499 employés",
        "19 employés et moins",
    ];

    const BUSINESS_KIND = [
        "Cabinet de recrutement",
        "Editeur de logiciel",
        "Cabinet de recrutement",
        "Cabinet de recrutement",
        "Cabinet de recrutement",
        "ESN / Cabinet de conseil",
        "Entreprise",
        "Cabinet de recrutement",
        "ESN / Cabinet de conseil",
        "Editeur de logiciel",
        "Editeur de logiciel",
        "Editeur de logiciel",
        "Cabinet de recrutement",
        "Entreprise",
        "Editeur de logiciel",
        "Cabinet de recrutement",
        "Editeur de logiciel",
        "Editeur de logiciel",
        "Entreprise",
        "ESN / Cabinet de conseil",
        "Entreprise",
        "Entreprise",
        "Entreprise",
        "ESN / Cabinet de conseil",
        "ESN / Cabinet de conseil",
        "ESN / Cabinet de conseil",
        "Entreprise",
        "Editeur de logiciel",
        "Entreprise",
        "ESN / Cabinet de conseil",
    ];

    const BUSINESS_ACTIVITY_AREA = [
        "Média & Télécom, Web & Tech, Industrie & Énergie, Jeux vidéo",
        "Web & Tech",
        "Web & Tech, Média & Télécom, Jeux vidéo, Industrie & Énergie, Banque & Assurance",
        "Jeux vidéo, Web & Tech, Média & Télécom, Industrie & Énergie, Banque & Assurance",
        "Web & Tech, Média & Télécom, Jeux vidéo, Industrie & Énergie, Banque & Assurance",
        "Banque & Assurance, Industrie & Énergie, Jeux vidéo, Média & Télécom, Web & Tech",
        "Média & Télécom, Web & Tech",
        "Autre, Consulting & Finance, Industrie & Énergie, Banque & Assurance, Jeux vidéo, Média & Télécom, Web & Tech",
        "Média & Télécom, Web & Tech",
        "Web & Tech, Industrie & Énergie",
        "Web & Tech, Industrie & Énergie",
        "Autre, Web & Tech, Média & Télécom",
        "Web & Tech",
        "Web & Tech, Média & Télécom",
        "Web & Tech",
        "Autre, Santé, Food & Beverage, Mode & Luxe, Consulting & Finance, Jeux vidéo, Web & Tech, Média & Télécom, Industrie & Énergie, Banque & Assurance",
        "Média & Télécom, Industrie & Énergie, Web & Tech",
        "Industrie & Énergie",
        "Santé, Web & Tech",
        "Web & Tech",
        "Autre, Web & Tech",
        "Web & Tech",
        "Santé",
        "Jeux vidéo, Web & Tech, Média & Télécom, Industrie & Énergie, Banque & Assurance",
        "Média & Télécom, Web & Tech, Industrie & Énergie, Banque & Assurance",
        "Consulting & Finance, Industrie & Énergie, Banque & Assurance",
        "Web & Tech",
        "Web & Tech",
        "Web & Tech",
        "Web & Tech, Industrie & Énergie, Banque & Assurance",
    ];

    const BUSINESS_LOCATION = [
        "43 rue du Général Leclerc, 44000 Reze",
        "203 rue de Bercy, 75012 Paris",
        "21 rue de Bonnel, 69003 Lyon",
        "311 chemin de la Sainte Allée, 83910 Pourrieres",
        "27 rue de la Clef, 75005 Paris-5E-Arrondissement",
        "162 rue de Rivoli, 75001 Paris",
        "20 rue Rouget de Lisle, 92130 Issy-les-Moulineaux",
        "70 avenue de l'Harmonie, 59262 Villeneuve-d'Ascq",
        "157 rue Anatole France, 92300 Levallois-Perret",
        "8 rue Rossini, 75009 Paris",
        "27 rue du Chemin Vert, 75011 Paris",
        "5 rue Legouvé, 75010 Paris",
        "96 bd Danielle Casanova, 13014 Marseille",
        "1 rue de l'ancien canal, 93500 Pantin",
        "132 rue Fondaudège, 33000 Bordeaux",
        "531 avenue du Prado, 13008 Marseille",
        "23 rue Crepet, 69007 Lyon",
        "2 route des Cornées, 10160 Aix-Villemaur-Palis",
        "1 rue de Stockholm, 75008 Paris",
        "2 rue de Valois, 75001 Paris",
        "46 rue Notre Dame des Victoires, 75002 Paris",
        "96 rue Aristide Briand, 92300 Levallois-Perret",
        "53 quai de la Seine, 75019 Paris",
        "14 boulevard de Bellechasse, 94100 Saint-Maur-des-Fossés",
        "5 boulevard Vincent Gâche, 44200 Nante",
        "119 rue du pdt Wilson, 92300 Levallois-Perret",
        "4 boulevard de Mons, 59650 Villeneuve-d'Ascq",
        "7 rue de Turi, 3378 Livange, Luxembourg",
        "136B rue de Grenelle, 75007 Paris",
        "4 boulevard Louis Tardy, 79000 Niort",
    ];

    const BUSINESS_DESCRIPTION = [
        "Externatic est un cabinet de recrutement technologique spécialisé en informatique. Nous ne proposons des postes que chez des clients finaux (éditeur, DSI, startup, agences, etc. = pas de SSII) dans le grand ouest (Nantes, Angers, Rennes, Bordeaux)./\nCentrés sur l'humain, nous fonctionnons en réseau. Nous sommes un réel hub d'opportunités dans l'Ouest aussi bien candidats que clients",
        "La mission de Dataiku est grande: permettre à toutes les personnes dans les entreprises du monde entier d'utiliser les données en supprimant les frictions entourant l'accès aux données, le nettoyage, la modélisation, le déploiement, etc.\n\nMais ce n'est pas seulement une question de technologie et de processus; Chez Dataiku, nous pensons également que les personnes (y compris les nôtres!) sont un élément essentiel de l'équation.",
        "HC RESOURCES est une société de conseil en ressources humaines intervenant sur le recrutement, formation, coaching, évaluation et assessment center.\n\nFondé en 1999, nous sommes spécialisés sur l'ensemble des métiers de l'IT. Nous intervenons exclusivement pour des clients finaux et 100% de nos missions sont en CDI.",
        "Konekt est une solution de recrutement spécialisée dans les métiers du digital.\n\nMettre au centre l'expertise technique et l'humain en s'appuyant sur des méthodes novatrices de matching, d'identification et d'évaluation sur une population de candidats actifs et passifs, voilà le défi de Konekt.",
        "Acteur indépendant du recrutement Tech sur Paris, UNLCK propose une sélection de postes directement en CDI chez des clients finaux.\n\nParce qu’un changement de poste n'est pas anodin et peut avoir un impact considérable sur votre carrière, nous revendiquons une approche (vraiment) technique, un accompagnement personnalisé, et des process totalement transparents pour faire le meilleur choix.\n\nQue vous soyez en recherche active ou simplement curieux du marché, n'hésitez pas à nous contacter.",
        "Créé en 2009, PALO IT est un cabinet de conseil et réalisation en innovation digitale. Présente en France (Paris, Toulouse, Nancy, Nantes et Lyon), à Hong Kong, à Singapour, à Bangkok, à Mexico et Sydney. La société rassemble une équipe de 400 experts passionnés de 27 nationalités.\n\nPALO IT accompagne les leaders de marché, les startups et les fonds d’investissement dans leur transformation digitale.",
        "Nous proposons des solutions et services d’hébergement infogérés (IaaS, PaaS, BaaS...), des solutions d'externalisation sur-mesure (Cloud Privé), des prestations de déploiement d’infrastructure système et réseau ainsi que des offres de Transit IP.\n\nNous sommes opérateur IP, possédons nos propres infrastructures Hosting/Réseau et gérons entièrement la connectivité.\n\nNous sommes présent dans différents Datacenters en Europe.",
        "Passionnés des RH, mordus d’innovation, résolument digital nos valeurs nous lient et nous animent au quotidien. Notre but ? Donner une nouvelle dimension à vos projets professionnels !\n\nVenez échanger avec nos équipes.\n\nExperts RH, chercheurs de Talents et consultants IT vous attendent !",
        "Développé à partir d'un embryon 100% digital, Ekino propose et concrétise des solutions innovantes de A à Z. Ses 220 collaborateurs passionnés conçoivent, réalisent et exploitent des solutions toujours plus novatrices et créatives au travers de dispositifs web et mobile.\n\nUne multitude de grands comptes, de Renault à SFR en passant par DigiPoste, font confiance à notre expertise technique reconnue et à notre compréhension des enjeux business pour donner corps à leur ambition digitale.",
        "En tant que Concepteur Développeur Talend Data Integration, vous aurez la responsabilité de construire les briques de l'ETL et de l'ESB de l'entreprise.\n\nVous travaillerez sur le développement d’applications et devrez également les tester et les documenter; tout en contribuant à la revue de code des autres développeurs de votre équipe et à la réussite de vos projets, en assistant votre responsable dans la gestion des tâches et l'élaboration du planning, tout en assurant une veille technologique permanente.\n\nEgalement vous serez en charge du suivi, de la mise à jour et de la diffusion des outils et des informations de l’entreprise. Force de proposition, vous êtes attentif aux éventuels dysfonctionnements et pouvez proposer des solutions efficaces.\n\nDans certains cas, vous rédigerez des spécifications fonctionnelles et vous réaliserez des tests unitaires jusqu'à la mise en production.\n\nEnfin, vous serez également garant de l'image d’ADBI, reconnue auprès de ses clients et partenaires",
        "Kostango est une start-up parisienne qui développe une solution digitale web ultra-moderne qui vise à disrupter les applications métiers vieillissantes des grands groupes.",
        "Eion est la prochaine plateforme d'image sur le web et sur mobile (IOS)\n\nEion est une plateforme gratuite dédiée à la création pour tous. Pour montrer, publier, communiquer, échanger, rencontrer, découvrir, collaborer, inspirer, expérimenter et créer ainsi une nouvelle communauté créative sans limites ni frontières.",
        "Recrut-info est un cabinet de recrutement dédié aux profils techniques autour du développement Web et dirigé par un Développeur qui dispose d'une expertise dans plusieurs domaines. Basé à Marseille, nous intervenons pour des entreprises de type 'clients finaux' qui se trouvent dans le département des Bouches-du-Rhône.\n\nNous procédons à une évaluation technique de chaque candidat pour nous assurer de son niveau de compétences et de son potentiel !",
        "BETC est une agence de publicité française fondée à Paris en 1995. Ses campagnes les plus remarquables sont destinées aux clients Air France, Evian et Canal +",
        "NuxtJS est un framework web Open Source crée fin 2016 basé sur Node.js, Webpack, Babel et Vue.js et à destination des développeur souhaitant créer des applications web nouvelle génération.",
        "LM5P est un cabinet de recrutement indépendant spécialiste des fonctions cadres et situé à Marseille et Avignon, en région PACA.",
        "Knitiv est une jeune société lyonnaise innovante, qui réinvente la gestion de données en entreprise.\n\nNous développons une solution cloud de gestion documentaire, gestion de projet, révolutionnaire, qui séduit chaque jour plus des clients, tout particulièrement dans les grands groupes du CAC40.",
        "Fisa édite depuis 1985 une gamme de logiciels en technologies Microsoft. Ces logiciels effectuent des calculs thermiques et des calculs de fluides pour le bâtiment : modélisation d’enveloppe, de systèmes thermiques, de production d’énergie renouvelable. Ils utilisent donc des algorithmes sophistiqués, basés sur les propriétés physiques du bâti et sur les données climatiques. Ils s’inscrivent dans la tendance du BIM (Building Information Modeling) et de la maquette numérique.",
        "Carenity est le 1er réseau social dédié aux patients et à leurs proches en Europe avec 300 000 membres inscrits.\n\nDepuis 2011, Carenity permet aux patients de partager leur expérience, suivre l’évolution de leur santé et contribuer à la recherche médicale en participant à des études en ligne.\n\nNotre mission : traduire les expériences individuelles des patients en connaissances mobilisables par les chercheurs et les industriels pour favoriser la mise au point d'une meilleure offre de soins et de services.",
        "Abbeal, c’est une communauté de développeurs passionnés qui partagent leur savoir-faire autour de stacks modernes.\n\nExpertise technique et esprit d’équipe sont les deux valeurs qui nous définissent, ce pourquoi nous recherchons des personnes motivées, passionnées et enthousiastes pour rejoindre nos équipes et accompagner nos partenaires sur le développement de leurs projets numériques.\n\nAbbeal offre également aux équipes la possibilité de développer leurs projets au sein d’AbbealValley, notre incubateur de Startup.",
        "Homerez est leader européen de la location de vacances avec pas loin de 10 000 propriétés. Nous révolutionnons le marché de la location saisonnière avec une offre unique et personnalisée à destination des propriétaires européens, s'appuyant sur une technologie 100% in-house et unique dans le monde de la location de vacances.\n\nNous offrons la meilleure des expériences à nos clients en valorisant leurs propriétés et en donnant une visibilité sans égale à leurs biens en nous entourant de « géants » de la réservation de logements saisonniers en ligne tels qu’AirBNB, Booking.com, Homeaway, etc...",
        "uTip construit la prochaine génération de plateformes de contenus, permettant à la fois de suivre les créatries et créateurs que l'on aime et de retrouver à un seul endroit tout ce qu'ils publient quel que soit le réseau, mais aussi de les soutenir.\n\nNous voulons rendre réalisable le rêve de millions de créateurs de contenus en ligne (vidéastes, dessinateurs, podcasts, écrivains...) qui cherchent une plateforme sur laquelle trouver un modèle économique leur permettant de vivre de leur passion de création de contenus.",
        "L'association loi 1901 et fédération nationale AVECsanté anime et développe le réseau des fédérations régionales des équipes de soins coordonnées. Elle anime ce réseau, représente et accompagne les fédérations régionales et les 1600 maisons de santé.",
        "KEOLIO est une Agence Digitale spécialisée dans le développement Business de ses clients. Du site Internet à la borne de commande en magasin, nous proposons un panel complet de solutions techniques et marketings.",
        "L’agence Aubay Grand Ouest compte 150 Consultants et Ingénieurs qui interviennent à 45% sur des activités d’étude et développement.\n\nDu Conseil à tout type de projet technologique, nous accompagnons la transformation et la modernisation des systèmes d’information de nos clients.\n\nPrésents depuis plus de 30 ans sur le bassin nantais, nous avons une très bonne connaissance du marché. De forts partenariats sont tissés tant avec des clients type PME/Start-up (industrie, édition de vote en ligne, audiovisuel/TV connectée) que des clients grands comptes (banque, assurance, transport).",
        "FAO Consulting a été créé en 2014. D'abord spécialiste des métiers de la finance de marché, elle développe en parallèle depuis 2016, une activité tournée vers les métiers de la transformation digitale. FAO Consulting est un cabinet de conseil à taille humaine qui se positionne comme un acteur de proximité auprès de ses clients et ses collaborateurs. Son ambition : continuer de valoriser les missions à fortes valeurs ajoutées, accompagner ses clients dans leurs projets et ses collaborateurs dans leur carrière.",
        "Decathlon, une enseigne et des marques innovantes pour le plaisir de tous les sportifs.\n\nChez Decathlon, nous sommes 70 000 collaborateurs à vivre au quotidien notre sens commun qui est de rendre accessibles au plus grand nombre le plaisir et les bienfaits du sport. Dans tous les pays où nous sommes présents, nous partageons une culture d’entreprise forte et unique renforcée par nos deux valeurs qui sont la vitalité et la responsabilité.",
        "Chez Ezbeez, nous avons inventé la Conciergerie Sociale!\n\nNos services (Ezpaie et Ezbenefits) s'appuient sur une plateforme pour : \n\n- Etablir simplement les bulletins de salaire et de transmettre les déclarations sociales automatiquement\n- S’occuper pour eux des relations avec les différentes caisses\n- Établir les certificats et autres documents de début ou de fin de contrat de  travail\n- Gérer un comité d’entreprise externalisé, qui donne accès à des nombreuses offres promotionnelles et remises afin d’améliorer le pouvoir d’achat des salariés et dirigeants",
        "Winamax est un site de jeux en ligne (poker, paris sportifs), l'un des plus fiables du marché. Les positions prises par Winamax dans le poker en ligne et dans les paris sportifs résultent d'une innovation constante tant sur le plan marketing&communication, que sur un plan technologique.\n\n Les informaticiens représentent un quart du Staff Winamax, actuellement de 180 salariés.",
        "Wekey accompagne ses clients dans leur transformation digitale et la conduite du changement en se concentrant sur trois principaux métiers : \n- Le pilotage de programmes et de projets\n- Le product management\n- L'expertise technique\nWekey accompagne également ses équipes à l'entrepreneuriat.\nEnfin, Wekey travaille sur l'émergence de nouvelles idées de start-up et accompagne les porteurs de projets dans la réussite de ces derniers.",
    ];

    const BUSINESS_WHYUS = [
        "Notre valeur ajoutée : nous sommes spécialisés dans notre domaine. Nous comprenons donc très bien les métiers et problématiques de nos clients, et nous savons parler aux profils les plus techniques de l'IT. Pourquoi Externatic disrupte la manière de recruter. Nous écoutons nos candidats L'idée étant de pouvoir conseiller en toute bienveillance nos candidats sur les entreprises et les missions qui leurs correspondent. Nous inversons donc le processus de recrutement pour permettre un meilleur matching entre les talents et les entreprises.",
        "Dataiku investit beaucoup dans ses employés et cela commence dès le début avec une grande semaine d'intégration à Paris (pour toutes les nouvelles embauches mondiales) et nos fameuses After Data Drinks jeudi durant cette semaine. Ils aiment rassembler les gens une fois par an dans un pays étranger pour travailler sur la stratégie mais aussi pour créer un lien fort entre les différentes équipes du monde entier. Du côté des avantages, ils ont également un grand espace de travail avec boissons, petit-déjeuner, jeux vidéo (PS4, Nintendo Switch), billard, baby-foot. Pour ceux qui aiment le vrai sport et pas seulement la FIFA ;-) ils proposent des cours de yoga.",
        "Parce que chez nous, on aime les pâtes et la muscu.",
        "Parce que chez nous, on aime les pâtes et la muscu.",
        "Chez UNLCK, nous proposons aussi bien des postes en Startup que chez des Grands Comptes; en passant par les PME et autres Pure Players. Notre panel d'opportunités est donc particulièrement large et tous les secteurs d'activité sont représentés.\n\nNous accompagnons et conseillons tous types de profils : jeune diplômé(e), autodidacte, développeur confirmé, senior, en reconversion professionnelle, lead dev, chef de projet technique, etc.\n\nTestez-nous :-)",
        "Palo IT est une entreprise apprenante guidée par les valeurs de l’agilité et du craftmanship vous offrant multiples possibilités de vous exprimer et de vous développer au travers de : - nos BarCamps mensuels - Nos projets R&D - nos practices (Big Data, Architecture, Agile, java/JS) - nos missions à forte valeur ajoutée en France et à l’international - nos participations et sponsoring des évènements techniques incontournables (Devoxx, Best Of Web, JUG etc.)",
        "Naitways c'est une équipe jeune et soudée composée d'ingénieurs passionnés, guidée par la soif de découvertes technologiques. Travailler au sein de Naitways c’est s’investir dans un projet professionnel et humain, dans un environnement de travail dynamique, multiculturel et ambitieux pour relever les défis de demain. Si tu es motivé, dynamique, passionné de nouvelles technologies et que pour toi travail rime avec bien être, équipe et challenge, tu as ta place chez nous !",
        "Parce que chez nous, on aime les pâtes et la muscu.",
        "Pour nos développeurs, UX/UI designers, chefs de projets, ingénieurs Devops, etc. travailler chez ekino c’est s’impliquer sur des projets innovants et variés, le tout dans une bonne ambiance et un environnement moteur. Récompensé au palmarès Great Place To Work 2016, ekino figure parmi les meilleures entreprises où il fait bon travailler en France 100% Techno: 75 projets lancés, 3 expériences NUI, 132 serveurs installés, 1200 tomcats déployés, 120 sites&apps mobiles/tablettes, 15000 commits sur 1 seul projet 99% Fun: 2 soirées d’agence, 223 petits déjeuners, 51 technoshare et projectshare...",
        "ADBI est une société de services informatiques basée à Paris. Nos solutions destinées aux PME et aux grands comptes tirent pleinement profit des solutions TALEND et HADOOP sur des problématiques de Data Management, Business Intelligence et Big Data afin d'optimiser le capital immatériel que représentent les données.",
        "Vous travaillerez sur des technos web ultra-modernes (Django, Angular2+, Docker) et serez responsabilisé très rapidement. Vous interviendrez aussi bien sur la conception que le développement, en front/back/devops. La conception et les choix architecturaux de la solution représentent un vrai challenge technique. Tous vos développements seront directement mis en production et utilisés par les grands groupes. Vous travaillerez dans un environnement start-up fun et dynamique dans une pépinière d'entreprises en plein cœur de Paris.",
        "Eion est une startup basé dans le  centre de Paris. eion c’est aussi beaucoup de musique, de café, un codebase sain et solide et des supers ordis.",
        "Grâce à notre expertise technique nous sommes uniquement mandaté par des entreprises qui recherchent des profils de qualité. Nous prenons le temps d'écouter vos attentes et de vous proposer diverses offres afin que vous puissiez choisir auprès de quelle société vous souhaitez candidater. Nous sommes ouverts aux profils autodidactes, nous pouvons vous évaluer afin de vous conseiller sur les postes qui correspondront à vos attentes et à votre niveau. Enfin et surtout, nous avons comme clients les sociétés les plus pointues des Bouches-du-Rhône sur le plan technique...",
        "Parce que chez nous, on aime les pâtes et la muscu.",
        "Parce que chez nous, on aime les pâtes et la muscu.",
        "Parce que chez nous, on aime les pâtes et la muscu.",
        "Petite équipe à taille humaine vous travaillerez en grande autonomie, animé par notre directeur technique qui est également co-fondateur de l'entreprise. Choisir Knitiv c'est la garantie de: - Manipuler au quotidien des concepts très innovants et stimulants intellectuellement - D'être accompagné par des personnes d'expérience - D'avoir de multiples perspectives d'évolution",
        "Parce que chez nous, on aime les pâtes et la muscu.",
        "Proposer un espace d'échanges aux patients tout en portant leurs voix auprès du milieu médical c'est un engagement fort auprès de ces personnes. En ce sens, les applications et services web que nous proposons doivent être à même de relever ce challenge par des approches et des réflexions innovantes. C'est à cela que vous serez amené à participer dans un environnement de start-up en plein cœur de St Lazare.",
        "Nous avons une organisation originale basée sur des récompenses, la notion de communauté et un bon équilibre entre vie personnelle et professionnelle. Nous sommes aussi très actifs dans l'écosystème numérique Parisien et Lyonnais. Notre Stack : - JS : React.js, Node.js, Vue.js, Angular - PHP : Symfony 3/4, Drupal 7/8 - Mobile : iOS (Swift), Android (Kotlin)",
        "Parce que chez nous, on aime les pâtes et la muscu.",
        "Avec 20 000 créateurs ayant rejoint la plateforme cette année et plus d'1,5M de visiteurs par mois, nous avons connu une croissance très rapide depuis le lancement de la plateforme il y a un an. La plateforme va être lancée à l'international début 2020. Nous travaillons sur la plus grande assymétrie du web : le travail des créateurs de contenus n'y est pas aussi respecté que dans d'autres domaines. Nous voulons résoudre ce problème à l'échelle internationale, tu veux nous aider ?",
        "Parce que chez nous, on aime les pâtes et la muscu.",
        "Parce que chez nous, on aime les pâtes et la muscu.",
        "Vous apprécierez notre management de proximité et la taille humaine de notre agence où nos Consultants Aubay seront à votre écoute pour tout retour d’expérience. Nous souhaitons vous accompagner dans votre évolution de carrière avec des formations et des certifications. Des accompagnements personnalisés sont également possibles, par exemple avec les coachs agiles de notre cellule interne. Enfin, votre bien-être reste notre priorité : vous bénéficierez de l’intervention de notre masseuse 2 fois par mois, des soirées d’agence ou encore de Serious Game pour apprendre et travailler en s’amusant.",
        "Parce que chez nous, on aime les pâtes et la muscu.",
        "Decathlon a une très grosse communauté IT, avec beaucoup de projets dans beaucoup de technos différentes. Au quotidien, les méthodologies mises en place et les technos utilisées sont récentes. Ta veille technologique n'est pas juste utile pour toi-même. Tu peux challenger l'existant, expérimenter, mettre en place des solutions innovantes dans un but d'amélioration continue.",
        "EMPLOI SYSTEME est la société propriétaire de la Conciergerie Sociale Numérique Ezbeez. Les boss Ugo Loustalet et André Martinie sont des leaders d'opinion en ce qui concerne la simplification administrative... Et il faut reconnaitre qu'il sont totalement 'habités' par le projet ce qui extrêmement motivant car ils savent gérer les interactions dev-ops afin de travailler avec fluidité. Ezbeez compte 1500 sociétés clientes aujourd'hui, plusieurs milliers demain.",
        "'Work hard, play harder', telle pourrait être notre devise. Nous sommes convaincus d'être une entreprise atypique. La performance, autant que la compétition et le fun sont dans l'ADN de WINAMAX. C'est ce qui fait notre réussite. Entrez dans le jeu.",
        "Nos valeurs : sens du service, entrepreneuriat, passion et expertise !",
    ];

    /**
     * UserFixture constructor.
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param FileUploaderService $fileUploaderService
     */
    public function __construct(UserPasswordEncoderInterface $passwordEncoder, FileService $fileService)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->fileService = $fileService;
    }

    /**
     * @param ObjectManager $manager
     */
    protected function loadData(ObjectManager $manager)
    {
        $this->createMany(User::class, 61, function(User $user) {
            if ($this->index % 2 === 0 && $this->index < 60) {
                $user
                    ->setEmail(self::DEFAULT_CANDIDATE . $this->index . self::DEFAULT_ENDMAIL)
                    ->setPassword($this->passwordEncoder->encodePassword($user, "12345678"))
                    ->setFirstName($this->faker->firstname)
                    ->setLastName($this->faker->lastname)
                    ->setPhoneNumber($this->faker->phoneNumber)
                    ->setWebSite($this->faker->url)
                    ->setIsActive(true)
                    ->setRoles(["ROLE_CANDIDATE"])
                    ->setBirthDay($this->faker->dateTimeBetween($startDate = "-60 years", $endDate = "- 18 years", $timezone = "Europe/Paris"))
                    ;

                    $chanceToHaveCv = rand(0, 3);

                    if ($chanceToHaveCv > 0) {
                        $fileName = self::CHOICES[rand(0, 1)];
                        $fileCopyName = $fileName . "copy";
                        $filePath = __DIR__ . "/UsersCvs/";
                        $fileExt = ".pdf";

                        $fileSrc = $filePath . $fileName . $fileExt;
                        $fileDest = $filePath . $fileCopyName . $fileExt;

                        $this->fileService->copyFile($fileSrc, $fileDest);

                        $cvFile = $this->fileService->createUploadFile($fileDest, $fileCopyName);

                        $cv = new Cv();
                        $cv
                            ->setName($fileName)
                            ->setCvFile($cvFile);

                        $user->setCv($cv);

                        $this->manager->persist($cv);
                    }
            } else if ($this->index % 2 !== 0 && $this->index < 60) {
                $business = new Business();

                $business
                    ->setSiretNumber(self::BUSINESS_SIRET_NUMBER[$this->business_index])
                    ->setName(self::BUSINESS_NAME[$this->business_index])
                    ->setEmployeesNumber(self::BUSINESS_EMPLOYEES_NUMBER[$this->business_index])
                    ->setKind(self::BUSINESS_KIND[$this->business_index])
                    ->setActivityArea(self::BUSINESS_ACTIVITY_AREA[$this->business_index])
                    ->setLocation(self::BUSINESS_LOCATION[$this->business_index])
                    ->setDescription(self::BUSINESS_DESCRIPTION[$this->business_index])
                    ->setWhyUs(self::BUSINESS_WHYUS[$this->business_index])
                    ;

                $fileName = $business->getName();
                $fileCopyName = $fileName . "copy";
                $filePath = __DIR__ . "/BusinessAvatars/";
                $fileExt = ".png";

                $fileSrc = $filePath . $fileName . $fileExt;
                $fileDest = $filePath . $fileCopyName . $fileExt;

                $this->fileService->copyFile($fileSrc, $fileDest);

                $avatarFile = $this->fileService->createUploadFile($fileDest, $fileCopyName);

                $avatar = new Avatar();
                $avatar
                    ->setName($fileName)
                    ->setAvatarFile($avatarFile);

                $business->setAvatar($avatar);

                $this->manager->persist($avatar);

                $user
                    ->setEmail(self::DEFAULT_RECRUITER . $this->index . self::DEFAULT_ENDMAIL)
                    ->setPassword($this->passwordEncoder->encodePassword($user, "12345678"))
                    ->setFirstName($this->faker->firstname)
                    ->setLastName($this->faker->lastname)
                    ->setIsActive(true)
                    ->setRoles(["ROLE_RECRUITER"])
                    ->setBirthDay($this->faker->dateTimeBetween($startDate = "-60 years", $endDate = "- 18 years", $timezone = "Europe/Paris"))
                    ->setBusiness($business)
                    ;
            } else {
                $user
                    ->setEmail("admin@findlab.com")
                    ->setPassword($this->passwordEncoder->encodePassword($user, "12345678"))
                    ->setFirstName("Robert")
                    ->setLastName("Hue")
                    ->setIsActive(true)
                    ->setRoles(["ROLE_ADMIN"])
                    ->setBirthDay($this->faker->dateTimeBetween($startDate = "-60 years", $endDate = "- 18 years", $timezone = "Europe/Paris"))
                    ;
            }

            $chanceToHaveAvatar = rand(0, 3);

            if ($chanceToHaveAvatar > 0) {
                $fileName = self::CHOICES[rand(0, 4)];
                $fileCopyName = $fileName . "copy";
                $filePath = __DIR__ . "/UsersAvatars/";
                $fileExt = ".jpg";

                $fileSrc = $filePath . $fileName . $fileExt;
                $fileDest = $filePath . $fileCopyName . $fileExt;

                $this->fileService->copyFile($fileSrc, $fileDest);

                $avatarFile = $this->fileService->createUploadFile($fileDest, $fileCopyName);

                $avatar = new Avatar();
                $avatar
                    ->setName($fileName)
                    ->setAvatarFile($avatarFile);

                $user->setAvatar($avatar);

                $this->manager->persist($avatar);
            }

            $this->index++;

             if ($this->index > 0 && $this->index % 2 === 0) {
                 $this->business_index++;
             }
        });

        $manager->flush();
    }
}