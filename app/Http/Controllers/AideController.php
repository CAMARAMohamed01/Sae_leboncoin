<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AideController extends Controller
{
    

    private function getFaqData()
    {
        return [
            'compte' => [
                'title' => 'Mon compte : gestion de profil, connexion et paramètres perso',
                'color' => 'orange',
                'faqs' => [
                    [
                        'q' => "Je n'arrive pas à me connecter à mon espace",
                        'r' => "Si vous rencontrez des difficultés pour accéder à votre compte, nous vous invitons d'abord à vérifier la saisie de votre adresse email et de votre mot de passe. Si le problème persiste, utilisez la fonction 'Mot de passe oublié' pour recevoir un lien de réinitialisation par email."
                    ],
                    [
                        'q' => "Je n'arrive pas à me créer un compte",
                        'r' => "Pour créer un compte il vous suffit de vous rendre dans l'espace se connecter en haut à droite de votre écran sur le top loader et de sélectionner le bouton 'créez un compte'"
                    ],
                    [
                        'q' => "Quelle est la procédure pour modifier mon adresse email ?",
                        'r' => "Pour mettre à jour votre adresse électronique, veuillez vous rendre dans la section 'Paramètres' de votre profil. Une fois la modification effectuée, un email de confirmation sera envoyé à votre nouvelle adresse pour valider le changement de manière sécurisée."
                    ],
                    [
                        'q' => "Je souhaite supprimer définitivement mon compte",
                        'r' => "Nous regrettons de vous voir partir. Pour procéder à la suppression de votre compte, vous devez contacter notre support technique via le formulaire de contact dédié. Notez bien que cette action est irréversible et entraînera la perte de toutes vos données et annonces."
                    ],
                    [
                        'q' => "J'ai oublié mon mot de passe, que faire ?",
                        'r' => "Pas de panique. Sur la page de connexion, cliquez sur le lien 'Mot de passe oublié'. Renseignez l'adresse email associée à votre compte et suivez les instructions envoyées par courriel pour définir un nouveau mot de passe sécurisé."
                    ],
                    [
                        'q' => "Comment ajouter ou modifier ma photo de profil ?",
                        'r' => "Rendez-vous dans votre espace personnel, onglet 'Mon Profil'. Cliquez sur l'icône de l'appareil photo ou sur votre image actuelle pour télécharger une nouvelle photo depuis votre appareil. Les formats JPG et PNG sont acceptés."
                    ],
                    [
                        'q' => "Où puis-je retrouver mes annonces favorites ?",
                        'r' => "Toutes les annonces que vous avez sauvegardées sont accessibles directement depuis l'onglet 'Mes Favoris' situé dans la barre de navigation principale de votre espace connecté."
                    ],
                    [
                        'q' => "Pourquoi mon compte a-t-il été bloqué ?",
                        'r' => "Un compte peut être suspendu en cas de non-respect de nos conditions générales d'utilisation. Si vous pensez qu'il s'agit d'une erreur, nous vous invitons à consulter vos emails pour voir le motif ou à contacter notre service client pour une révision de votre dossier."
                    ],
                    [
                        'q' => "Est-il possible de modifier mon nom d'utilisateur ?",
                        'r' => "Oui, vous pouvez changer votre pseudo depuis les paramètres de votre profil. Toutefois, par mesure de sécurité et pour éviter les abus, cette modification n'est autorisée qu'une seule fois tous les 30 jours."
                    ],
                    [
                        'q' => "Puis-je partager mes annonces sur les réseaux sociaux ?",
                        'r' => "Absolument. Pour faciliter vos partages, utilisez les icônes de partage situées sur la fiche de votre annonce."
                    ]
                ]
            ],
            'annonces' => [
                'title' => 'Dépôt & Gestion : publier une annonce, la modifier',
                'color' => 'blue',
                'faqs' => [
                    [
                        'q' => "Le dépôt d'une annonce est-il payant ?",
                        'r' => "La publication d'annonces est entièrement gratuite pour les particuliers. Vous pouvez vendre vos objets sans aucuns frais de mise en ligne. Des options payantes de mise en avant sont toutefois disponibles si vous souhaitez accélérer la vente."
                    ],
                    [
                        'q' => "Combien de photos puis-je ajouter à mon annonce ?",
                        'r' => "Afin de présenter au mieux votre bien, notre plateforme vous permet d'ajouter jusqu'à 10 photos par annonce. Nous vous recommandons d'utiliser des images claires et bien éclairées pour attirer les acheteurs."
                    ],
                    [
                        'q' => "Comment puis-je modifier une annonce déjà en ligne ?",
                        'r' => "Connectez-vous à votre compte et allez dans la section 'Mes annonces'. Repérez l'annonce concernée et cliquez sur l'icône en forme de crayon 'Modifier'. Vous pourrez alors changer la description, le prix ou les photos."
                    ],
                    [
                        'q' => "Pourquoi mon annonce a-t-elle été refusée ?",
                        'r' => "Chaque annonce est vérifiée dans la journée de la publication. Si la vôtre a été refusée, c'est qu'elle ne respecte probablement pas notre charte de bonne conduite (article interdit, doublon, description inappropriée). Vérifiez l'email que nous vous avons envoyé pour plus de détails."
                    ],
                    [
                        'q' => "Quelle est la durée de validité de mon annonce ?",
                        'r' => "Une annonce reste active sur notre site pour une durée de 30 jours. Quelques jours avant son expiration, vous recevrez un email vous proposant de la renouveler gratuitement si votre bien n'est pas encore vendu."
                    ],
                    [
                        'q' => "Avez-vous des conseils pour vendre plus rapidement ?",
                        'r' => "Pour maximiser vos chances, rédigez un titre précis, une description détaillée et honnête, et fixez un prix cohérent avec le marché. Les annonces comportant plusieurs photos de bonne qualité se vendent statistiquement beaucoup plus vite."
                    ],
                    [
                        'q' => "Je ne retrouve plus mon annonce sur le site, pourquoi ?",
                        'r' => "Si votre annonce n'apparaît plus, il est possible qu'elle ait expiré après 30 jours ou qu'elle ait été retirée par notre équipe de modération suite à un signalement. Vérifiez le statut de vos annonces dans votre tableau de bord."
                    ],
                    [
                        'q' => "Est-il possible d'ajouter une vidéo de mon annonce ?",
                        'r' => "Actuellement, notre plateforme ne prend en charge que les fichiers images (photos). Nous travaillons cependant à l'intégration de la vidéo pour les futures mises à jour du site afin d'enrichir l'expérience utilisateur."
                    ],
                    [
                        'q' => "Comment modifier le prix de ma location ?",
                        'r' => "Vous pouvez ajuster le prix de location à tout moment. Il vous suffit d'éditer votre annonce via votre espace personnel. Une baisse de prix significative peut également envoyer une notification aux utilisateurs ayant mis votre annonce en favori."
                    ]
                ]
            ],
            'securite' => [
                'title' => 'Sécurité : conseils, paiement, vigilance',
                'color' => 'green',
                'faqs' => [
                    [
                        'q' => "Comment me protéger contre les arnaques par SMS ?",
                        'r' => "Soyez très vigilant si vous recevez un SMS vous demandant de contacter le vendeur par email ou de cliquer sur un lien suspect. Ne sortez jamais de la messagerie sécurisée de notre plateforme et ne communiquez jamais vos informations personnelles par SMS."
                    ],
                    [
                        'q' => "Le paiement en ligne est-il vraiment sécurisé ?",
                        'r' => "Oui, nous utilisons un protocole de cryptage SSL avancé pour toutes les transactions. En passant par notre système de paiement intégré, vos fonds sont séquestrés jusqu'à la confirmation de la réception du colis, garantissant ainsi la sécurité des deux parties."
                    ],
                    [
                        'q' => "Comment repérer un vendeur ou une annonce suspecte ?",
                        'r' => "Méfiez-vous des offres trop alléchantes avec des prix bien en dessous du marché. Une annonce sans photo, rédigée dans un français approximatif ou un vendeur qui insiste pour être payé par mandat cash sont souvent des signes d'arnaque."
                    ],
                    [
                        'q' => "On me demande un paiement par mandat cash ou Western Union",
                        'r' => "Refusez catégoriquement. Ces moyens de paiement ne permettent aucune traçabilité ni aucun recours en cas de problème. Les escrocs privilégient ces méthodes. Utilisez uniquement le paiement sécurisé du site ou une remise en main propre."
                    ],
                    [
                        'q' => "J'ai reçu un email me demandant mon mot de passe",
                        'r' => "Il s'agit d'une tentative de 'Phishing' (hameçonnage). Notre équipe ne vous demandera jamais votre mot de passe par email. Ne cliquez sur aucun lien et supprimez immédiatement le message. Signalez-le nous si possible."
                    ],
                    [
                        'q' => "Comment signaler un comportement abusif ?",
                        'r' => "Si vous tombez sur une annonce illégale ou un utilisateur au comportement déplacé, utilisez le bouton 'Signaler' présent sur chaque page d'annonce et de profil. Nos équipes de modération analyseront le signalement dans les plus brefs délais."
                    ],
                    [
                        'q' => "Mes données bancaires sont-elles partagées avec le vendeur ?",
                        'r' => "Absolument pas. Lors d'un paiement sécurisé, le vendeur n'a jamais accès à vos numéros de carte bancaire ou à vos coordonnées financières. Tout est géré par notre prestataire de paiement agréé."
                    ],
                    [
                        'q' => "Attention aux numéros surtaxés",
                        'r' => "Si un interlocuteur vous demande de rappeler un numéro commençant par 08 ou un numéro étranger pour 'arranger la vente', ne le faites pas. Il s'agit souvent d'arnaques aux numéros surtaxés visant à vous facturer des communications hors de prix."
                    ],
                    [
                        'q' => "Comment vérifier que je suis bien sur le site officiel ?",
                        'r' => "Les fraudeurs créent parfois de fausses copies de notre site pour voler vos identifiants. Vérifiez toujours que l'adresse dans votre navigateur correspond exactement à notre URL officielle => 51.83.36.122..."
                    ]
                ]
            ]
        ];

    }

    public function index()
    {
        return view('aide.index');
    }

    public function show($category)
    {
        $data = $this->getFaqData(); // On récupère les données proprement

        if (!array_key_exists($category, $data)) {
            abort(404);
        }

        return view('aide.show', [
            'categoryData' => $data[$category],
            'categoryName' => $category
        ]);
    }

    // LA NOUVELLE FONCTION DE RECHERCHE
    public function search(Request $request)
    {
        $query = $request->input('q'); // Ce que le mec a tapé
        $results = [];
        $data = $this->getFaqData();

        // Si la recherche n'est pas vide, on fouille partout
        if ($query) {
            foreach ($data as $catKey => $category) {
                foreach ($category['faqs'] as $faq) {
                    // On cherche dans la Question (q) ET la Réponse (r)
                    // stripos = recherche insensible à la casse (majuscule/minuscule on s'en fout)
                    if (stripos($faq['q'], $query) !== false || stripos($faq['r'], $query) !== false) {
                        // On ajoute la catégorie pour savoir d'où ça vient
                        $faq['category_slug'] = $catKey;
                        $faq['category_color'] = $category['color'];
                        $results[] = $faq;
                    }
                }
            }
        }

        return view('aide.search', [
            'results' => $results,
            'query' => $query
        ]);
    }
}