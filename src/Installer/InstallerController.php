<?php
namespace Neutrino\Installer;

use eftec\bladeone\BladeOne;
use Error;
use Exception;

use Neutrino\Api\Form\Bootstrap\EmailElement;
use Neutrino\Api\Form\Bootstrap\TextAreaElement;
use Neutrino\Database\Database;

use Neutrino\Api\Controller;
use Neutrino\Api\Form\Form;
use Neutrino\Api\Form\Bootstrap\SubmitElement;
use Neutrino\Api\Form\Bootstrap\TextElement;

/**
 * Ce controller permet de gérer l'installation du CMS. Il sera là pour initialiser la base de donnée et le compte
 * super admin du propriétaire ainsi que d'autre donnée nécessaire au bon fonctionnement du CMS.
 *
 * @package Neutrino\Installer
 */
class InstallerController extends Controller
{
    /**
     * Cette méthode est appeler lors que l'utilisateur lance la première fois le site afin de l'installer correctement.
     */
    public function install(): void
    {
        /*
         * Si la clé 'database' n'existe pas dans la session alors on lance la première étape de l'installation.
         * C'est à dire, les données à utiliser pour la connexion vers la base de donnée.
         */
        if (empty($_SESSION['database'])) {
            $this->installStep1();
            return;
        }

        /*
         * Sinon si la clé 'user' n'existe pas dans la session alors on lance la second étape de l'installation.
         * C'est a dire, les identifiants qui sera le super admin du site.
         */
        if (empty($_SESSION['user'])) {
            $this->installStep2();
            return;
        }

        /*
         * Sinon on passe à la dernière étape de l'installation, soit la mise en place de la configuration du CMS.
         */
        $this->installStep3();
    }

    /**
     * Cette méthode permet à l'utilisateur d'indiquer les informations de la base de donnée à utiliser.
     */
    private function installStep1(): void
    {
        /*
         * Création du formulaire que l'utilisateur devra utiliser pour remplir les données de la base de donnée.
         */
        $form = (new Form($_POST))
            ->add(new TextElement('Host', 'host', null, [
                'required' => false
            ], '127.0.0.1'))
            ->add(new TextElement('Port', 'port', null, [
                'matches'  => '/^[0-9]{2,5}$/',
                'required' => false
            ], '3306'))
            ->add(new TextElement('Nom de la base *', 'dbname'))
            ->add(new TextElement('Utilisateur *', 'user'))
            ->add((new TextElement('Mot de passe *', 'password', null, [
                'required' => false
            ]))->setType('password'))
            ->add(new TextElement('Prefix des tables', 'prefix', null, [
                'matches'  => '/^[a-zA-Z_]+$/',
                'required' => false
            ], 'ns_'))
            ->add(new SubmitElement('Suivant <i class="fas fa-arrow-circle-right"></i>'));

        /*
         * Si le formulaire à été soumis et qu'il est valide alors on vérifie que les identifiants sont corrects.
         */
        if ($form->isSubmit() && $form->isValid()) {
            $array = [
                'host'      => $form->get('url') ?: '127.0.0.1',
                'port'      => $form->get('port') ?: 3306,
                'dbname'    => $form->get('dbname'),
                'user'      => $form->get('user'),
                'password'  => $form->get('password'),
                'prefix'    => $form->get('prefix')
            ];
            /*
             * Si les coordonnées vers la base de donnée sont correctes, alors on affiche une vue de succès afin que
             * l'utilisateur puisse passer à la suite de l'installation.
             */
            if ($this->getDatabase($array) !== null) {
                /*
                 * On sauvegarde provisoirement les données saisies dans la session utilisateur afin de passer à la
                 * suite de l'installation du CMS.
                 */
                $_SESSION['database'] = $array;
                $this->render('installer-success', [
                    'success' => 'Félicitation, la connexion avec la base de donnée à réussi.',
                    'end' => false
                ]);
                return;
            }
            /*
             * Sinon on indique une erreur de connexion vers la base de donnée.
             */
            $errors = [ 'La connexion vers la base de donnée à échoué !' ];
        }

        /*
         * On rend la vue avec le formulaire pour saisir les coordonnées de la base de donnée.
         */
        $this->render('installer-init', [
            'h1' => 'Informations de la base de donnée',
            'form' => $form,
            'errors' => $errors ?? []
        ]);
    }

    /**
     * Cette méthode permet à l'utilisateur de saisir les informations pour le compte qui sera utilisé en tant que
     * super admin.
     */
    private function installStep2(): void
    {
        /*
         * Création du formulaire que l'utilisateur devra remplir pour initialiser un compte super admin
         */
        $form = (new Form($_POST))
            ->add(new TextElement('Nom *', 'name', null, [
                'min' => 3, 'max' => 50
            ]))
            ->add(new TextElement('Prénom *', 'lastname', null, [
                'min' => 3, 'max' => 50
            ]))
            ->add(new EmailElement('Email *', 'email'))
            ->add((new TextElement('Mot de passe *', 'password', null, [
                'min' => 6, 'max' => 32
            ]))->setType('password'))
            ->add((new TextElement('Confirmer le mot de passe *', 'password_confirm', null, [
                'min' => 6, 'max' => 32
            ]))->setType('password'))
            ->add(new SubmitElement('Suivant <i class="fas fa-arrow-circle-right"></i>'));

        /*
         * Si le formulaire a été soumis et qu'il est valide, alors on check si les mots de passe sont identiques.
         */
        if ($form->isSubmit() && $form->isValid()) {
            if ($form->get('password') === $form->get('password_confirm')) {
                $_SESSION['user'] = [
                    'name' => $form->get('name'),
                    'lastname' => $form->get('lastname'),
                    'email' => $form->get('email'),
                    'password' => password_hash($form->get('password'), PASSWORD_ARGON2ID, [
                        'cost' => 12
                    ])
                ];

                $this->render('installer-success', [
                    'success' => 'Bienvenue '.$form->get('lastname').' '.$form->get('name')
                        .', vous avez bientôt terminé l\'installation.',
                    'end' => false
                ]);
                return;
            }

            /*
             * Sinon on retourne une erreur comme quoi les mots de passe ne correspondent pas.
             */
            $errors = [ 'Les mots de passe ne sont pas identiques !' ];
        }
        /*
         * On rend la vue avec le formulaire pour saisir les informations de l'utilisateur.
         */
        $this->render('installer-init', [
            'h1'     => 'Informations de le Super Admin',
            'form'   => $form,
            'errors' => $errors ?? []
        ]);
    }

    /**
     * Cette méthode est la dernière étape du processus d'installation et permet à l'utilisateur de completer
     * la configuration du site.
     */
    private function installStep3(): void
    {
        /*
         * Création du formulaire que l'utilisateur devra remplir pour initialiser la configuration du site
         */
        $form = (new Form($_POST))
            ->add(new TextElement('Titre du site *', 'title', null, [
                'min' => 3, 'max' => 255
            ]))
            ->add(new TextAreaElement('Description du site', 'description', null, [
                'required' => false
            ]))
            ->add(new TextElement('Chemin vers le panneau d\'administration', 'admin_path', null, [
                'matches'  => '/^\/[a-zA-Z0-9-_]+$/',
                'required' => false
            ], '/admin'))
            ->add(new SubmitElement('<i class="fas fa-shield-alt"></i> Installer'));

        if ($form->isSubmit() && $form->isValid()) {
            $this->installer($_SESSION['database'], $_SESSION['user'], [
                'title'       => $form->get('title'),
                'description' => $form->get('description') ?: '',
                'adminPath'   => $form->get('admin_path') ?: '/admin'
            ]);
            $_SESSION = [];
            session_destroy();
            $this->render('installer-success', [
                'success'   => 'Félicitation ! L\'installation est maintenant terminé !',
                'end'       => true,
                'adminPath' => $form->get('admin_path') ?: '/admin'
            ]);
            return;
        }

        /*
         * On rend la vue avec le formulaire pour saisir les informations du site.
         */
        $this->render('installer-init', [
            'h1'     => 'Informations du site',
            'form'   => $form,
            'errors' => []
        ]);
    }

    private function installer(array $database, array $user, array $configuration)
    {

    }

    /**
     * Cette méthode permet d'ouvrir une connexion vers la base de donnée avec les données saisies par l'utilisateur.
     *
     * @param array $data Les données saisies par l'utilisateur pour les identifiants de la base de donnée.
     * @return Database|null
     */
    private function getDatabase(array $data): ?Database
    {
        try {
            return new Database($data['dbname'], [
                'host' => $data['host'],
                'port' => $data['port'],
                'user'  => $data['user'],
                'password'  => $data['password']
            ]);
        } catch (Exception | Error $error) {
            // Les informations saisies par l'utilisateurs ne doivent pas être correct.
        }
        return null;
    }

    /**
     * Cette méthode permet de réécrire le rendu spécialement pour ce controller.
     *
     * @param string $view  La chemin vers la vue à afficher.
     * @param array $params Les paramètres à envoyer à la vue.
     */
    public function render(string $view, array $params = []): void
    {
        echo (new BladeOne([__DIR__.'/views'], __DIR__.'/cache'))->run($view, $params);
    }
}
