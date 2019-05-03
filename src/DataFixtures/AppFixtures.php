<?php

namespace App\DataFixtures;
use Faker\Factory;
use App\Entity\Role;
use App\Entity\User;
use Faker\ORM\Doctrine\Populator;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Quizz;
use App\Entity\World;
use App\Entity\Category;
use App\Entity\Question;
use App\Entity\Answer;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $generator = Factory::create('fr_FR');
        $generator->seed('ochildren');

        $populator = new Populator($generator, $manager);

        // ROLE
        $roleUser = new Role();
        $roleUser->setName('ROLE_USER');
        $roleUser->setLabel('Utilisateur');
        $manager->persist($roleUser);

        $roleAdmin = new Role();
        $roleAdmin->setName('ROLE_ADMIN');
        $roleAdmin->setLabel('Administrateur');
        $manager->persist($roleAdmin);

        // WORLD
        $world36 = new World();
        $world36->setName("3-6 ans");
        $world36->setDescription("Activités pour les 3 à 6 ans");
        // $world36->setImage("");
        $manager->persist($world36);

        $world710 = new World();
        $world710->setName("7-10 ans");
        $world710->setDescription("Activités pour les 7 à 10 ans");
        // $world710->setImage("");
        $manager->persist($world710);

        // CATEGORY
        $catSpace = new Category();
        $catSpace->setName("Espace");
        $catSpace->setDescription("Ici retrouvez les activités sur le thème de l'espace");
        // $catSpace->setImage("");
        $manager->persist($catSpace);

        $catScience = new Category();
        $catScience->setName("Science");
        $catScience->setDescription("Ici retrouvez les activités sur le thème de la science");
        // $catScience->setImage("");
        $manager->persist($catScience);

        $catNature = new Category();
        $catNature->setName("Nature");
        $catNature->setDescription("Ici retrouvez les activités sur le thème de la nature");
        // $catNature->setImage("");
        $manager->persist($catNature);

        // USER
        $lorenzo = new User();
        $lorenzo->setEmail("lorenzo.marozzo@gmail.com");
        $lorenzo->setPassword("ochildren"); 
        $lorenzo->setUsername("Grandpere");
        $lorenzo->setFirstname("Lorenzo");
        $lorenzo->setImage("https://robohash.org/".$lorenzo->getEmail()."?set=set".mt_rand(1, 4)); 
        // https://robohash.org/ ou http://avatars.adorable.io/#demo
        $lorenzo->setBirthday(\Datetime::createFromFormat('j-m-Y', '03-06-1987'));
        $lorenzo->setRole($roleAdmin);
        $manager->persist($lorenzo);

        $mathieu = new User();
        $mathieu->setEmail("mathieuoliveirapereira@gmail.com");
        $mathieu->setPassword("ochildren");
        $mathieu->setUsername("MathieuOP");
        $mathieu->setFirstname("Mathieu");
        $mathieu->setImage("https://robohash.org/".$mathieu->getEmail()."?set=set".mt_rand(1, 4));
        $mathieu->setBirthday($generator->dateTimeInInterval($startDate = '-10 years', $interval = '-7 years'));
        $mathieu->setRole($roleAdmin);
        $manager->persist($mathieu);

        $anatole = new User();
        $anatole->setEmail("anatolelucet@gmail.com");
        $anatole->setPassword("ochildren");
        $anatole->setUsername("AnatoleLucet");
        $anatole->setFirstname("Anatole");
        $anatole->setImage("https://robohash.org/".$anatole->getEmail()."?set=set".mt_rand(1, 4));
        $anatole->setBirthday($generator->dateTimeInInterval($startDate = '-10 years', $interval = '-7 years'));
        $anatole->setRole($roleAdmin);
        $manager->persist($anatole);

        $philippe = new User();
        $philippe->setEmail("philippechiarla@gmail.com");
        $philippe->setPassword("ochildren");
        $philippe->setUsername("phil2611");
        $philippe->setFirstname("Philippe");
        $philippe->setImage("https://robohash.org/".$philippe->getEmail()."?set=set".mt_rand(1, 4));
        $philippe->setBirthday($generator->dateTimeInInterval($startDate = '-10 years', $interval = '-7 years'));
        $philippe->setRole($roleAdmin);
        $manager->persist($philippe);

        // PUZZLE

        // QUIZZ
        $quizzPlanet = new Quizz();
        $quizzPlanet->setTitle("Les planètes");
        $quizzPlanet->setDescription("Découvrons ensemble les planètes");
        $quizzPlanet->setWorld($world710);
        $quizzPlanet->addCategory($catSpace);
        // $quizzPlanet->setImage("")
        $manager->persist($quizzPlanet);

        $quizzNature = new Quizz();
        $quizzNature->setTitle("La nature");
        $quizzNature->setDescription("Découvrons ensemble la nature");
        $quizzNature->setWorld($world710);
        $quizzNature->addCategory($catNature);
        // $quizzNature->setImage("")
        $manager->persist($quizzNature);

        $quizzScience = new Quizz();
        $quizzScience->setTitle("La science");
        $quizzScience->setDescription("Découvrons ensemble la science");
        $quizzScience->setWorld($world710);
        $quizzScience->addCategory($catScience);
        // $quizzScience->setImage("")
        $manager->persist($quizzScience);

        // QUESTION / ANSWER
        $questionsAnswersPlanet = [
            [
                'question' => 'Combien de planètes existent dans le système solaire ?', 
                'answers' => [
                    '8',
                    '5',
                    '100',
                    '9'
                ]
            ],
            [
                'question' => 'Combien de temps met la Terre pour faire un tour sur elle même ?',
                'answers' => [
                    '24 h',
                    '25 h',
                    '7 jours',
                    '10 min'
                ]
            ],
            [
                'question' => 'Quelle planète n\'est pas une géante gazuse ?',
                'answers' => [
                    'Mars',
                    'Jupiter',
                    'Neptune',
                    'Saturne'
                ]
            ],
            [
                'question' => 'La Terre est composée d\'noyau, d\'une croûte et ?',
                'answers' => [
                    'Un manteau',
                    'Un chapeau',
                    'Une botte',
                    'Une combinaison spatiale'
                ]
            ],
            [
                'question' => 'Est ce que la Terre tourne autour du Soleil ?',
                'answers' => [
                    'Oui',
                    'Non',
                ]
            ],
            [
                'question' => 'Quelle planète est entourée par un anneau ?',
                'answers' => [
                    'Saturne',
                    'Mercure',
                    'Mars',
                    'Neptune'
                ]
            ],
            [
                'question' => 'Quelle planète est appelée "La planète rouge" ?',
                'answers' => [
                    'Mars',
                    'Neptune',
                    'La Terre',
                    'Mercure'
                ]
            ],
            [
                'question' => 'Qu\'est ce qu\'est le Soleil ?',
                'answers' => [
                    'Une étoile',
                    'Une planète',
                    'Un satellite',
                    'Un truc qui brille et qui donne chaud'
                ]
            ],
            [
                'question' => 'Quelle est la plus grosse planète du système Solaire ?',
                'answers' => [
                    'Jupiter',
                    'Mercure',
                    'Le soleil',
                    'Mars'
                ]
            ],
            [
                'question' => 'Dans de bonnes conditions, combien d\'étoiles peut-on observer dans le ciel ?',
                'answers' => [
                    '3000',
                    '500',
                    '1000',
                    '50000'
                ]
            ]
        ];
        foreach($questionsAnswersPlanet as $questionContent) {
            $question = new Question();
            $question->setContent($questionContent['question']);
            $question->setQuizz($quizzPlanet);
            // $question->setImage("");
            $manager->persist($question);

            $answerObjectList = [];
            foreach($questionContent['answers'] as $answerContent) {
                $answer = new Answer();
                $answer->setContent($answerContent);
                $answer->setQuestion($question);
                // $answer->setImage();
                $manager->persist($answer);
                $answerObjectList[] = $answer;
            }
            $question->setRightAnswer($answerObjectList[0]);
        }

        $questionsAnswersNature = [
            [
                'question' => 'Qu\'est ce qu\'un serpent ?',
                'answers' => [
                    'Un reptile',
                    'Un poisson',
                    'Un animal à poils',
                    'Une peluche'
                ]
            ],
            [
                'question' => 'Quel est le goût de l\'eau de mer ?',
                'answers' => [
                    'Salée',
                    'Sucrée',
                    'Pimentée',
                    'Acide'
                ]
            ],
            [
                'question' => 'Que trouves-t-on le plus dans le désert ?',
                'answers' => [
                    'Du sable',
                    'De l\'eau',
                    'Des forêts',
                    'Des champignons'
                ]
            ],
            [
                'question' => 'Quel animal vit dans le désert ?',
                'answers' => [
                    'Le chameau',
                    'Le lama',
                    'La vache',
                    'Le lion'
                ]
            ],
            [
                'question' => 'Quel animal possède une bosse ?',
                'answers' => [
                    'Le dromadaire',
                    'Le chameau',
                    'Le lama'
                ]
            ],
            [
                'question' => 'Quel insecte fabrique du miel ?',
                'answers' => [
                    'L\'abeille',
                    'La guêpe',
                    'La coccinelle',
                    'Le bourdon'
                ]
            ],
            [
                'question' => 'Pourquoi les plantes se fanent-elles ?',
                'answers' => [
                    'Elle manque d\'eau',
                    'Elle est vieille',
                    'Elle manque de terre',
                    'Elle manque d\'amour'
                ]
            ],
            [
                'question' => 'Quel animal voit dans le noir ?',
                'answers' => [
                    'Le hibou',
                    'Le chien',
                    'Le loup',
                    'Le lion'
                ]
            ],
            [
                'question' => 'Q\'est ce que la faune ?',
                'answers' => [
                    'L\'ensemble de tous les animaux',
                    'L\'abbréviation de téléphone en français',
                    'Une jungle',
                    'Les animaux à poils'
                ]
            ],
            [
                'question' => 'Les oiseaux et les tortues pondent des oeufs, on dit qu\'ils sont ?',
                'answers' => [
                    'Ovipare',
                    'Oeufidés',
                    'Ovidés',
                    'Pas comme moi'
                ]
            ]
        ];
        foreach($questionsAnswersNature as $questionContent) {
            $question = new Question();
            $question->setContent($questionContent['question']);
            $question->setQuizz($quizzNature);
            // $question->setImage("");
            $manager->persist($question);

            $answerObjectList = [];
            foreach($questionContent['answers'] as $answerContent) {
                $answer = new Answer();
                $answer->setContent($answerContent);
                $answer->setQuestion($question);
                // $answer->setImage();
                $manager->persist($answer);
                $answerObjectList[] = $answer;
            }
            $question->setRightAnswer($answerObjectList[0]);
        }

        $questionsAnswersScience = [
            [
                'question' => 'Quel est le gaz que nous respirons ?',
                'answers' => [
                    'Oxygène',
                    'Azote',
                    'CO2 (Dioxyde de Carbone)',
                    'Méthane'
                ]
            ],      
            [
                'question' => 'Comment s\'appelle la molécule de l\'eau ?',
                'answers' => [
                    'H2O',
                    'NYC',
                    'O2',
                    'LOL'
                ]
            ],      
            [
                'question' => 'Qui fabrique les médicaments ?',
                'answers' => [
                    'Le chimiste',
                    'Le docteur',
                    'Le boulanger',
                    'Le professeur'
                ]
            ],      
            [
                'question' => 'Que peut-on faire grâce à la science ?',
                'answers' => [
                    'Voir les microbes et les virus',
                    'Manger sans les mains',
                    'Dormir',
                    'Voir les animaux'
                ]
            ],      
            [
                'question' => 'Est-ce que la science nous a permis d\'aller dans l\'espace ?',
                'answers' => [
                    'Oui',
                    'Non'
                ]
            ],      
            [
                'question' => 'Pourquoi les scientifiques portent des gants, des lunettes et un masque ?',
                'answers' => [
                    'Pour se protéger',
                    'Pour se déguiser',
                    'Pour jouer',
                    'Pour ne pas se salir'
                ]
            ],      
            [
                'question' => 'Qui a découvert le vaccin contre la rage ?',
                'answers' => [
                    'Louis Pasteur',
                    'Un copain',
                    'Albert Einstein',
                    'Hippopotamus'
                ]
            ],      
            [
                'question' => 'A quelle température l\'eau gèle-t-elle ?',
                'answers' => [
                    'Autour de 0°c',
                    'Autour de -10°c',
                    'Autour de 100°c',
                    'Autour de -100°c'
                ]
            ],      
            [
                'question' => 'La science nous a permis de créer des robots ?',
                'answers' => [
                    'Oui',
                    'Non'
                ]
            ],      
            [
                'question' => 'Qu\'est ce que de l\'or ?',
                'answers' => [
                    'Un métal',
                    'Une roche',
                    'Un Liquide',
                    'Un bijoux'
                ]
            ],      
        ];
        foreach($questionsAnswersScience as $questionContent) {
            $question = new Question();
            $question->setContent($questionContent['question']);
            $question->setQuizz($quizzScience);
            // $question->setImage("");
            $manager->persist($question);

            $answerObjectList = [];
            foreach($questionContent['answers'] as $answerContent) {
                $answer = new Answer();
                $answer->setContent($answerContent);
                $answer->setQuestion($question);
                // $answer->setImage();
                $manager->persist($answer);
                $answerObjectList[] = $answer;
            }
            $question->setRightAnswer($answerObjectList[0]);
        }

        // TODO: si ajout de données pseudo aléatoire on poursuivra ici

        // $inserted = $populator->execute();

        $manager->flush();
    }
}