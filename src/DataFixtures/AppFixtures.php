<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\Contact;
use App\Entity\Newsletter;
use App\Entity\Post;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create();

        for($i = 0; $i < 10; $i++) {
            //User
            $user = new User();
            $user->setUsername($faker->userName);
            $user->setRoles(['ROLE_USER']);
            $user->setPassword($faker->password);
            $manager->persist($user);

            //Contact
            $contact = new Contact();
            $contact->setName($faker->name);
            $contact->setEmail($faker->email);
            $contact->setMessage($faker->paragraph);
            $contact->setDate($faker->dateTime($max = 'now', $timezone = null));
            $manager->persist($contact);

            //Newsletter
            $newsletter = new Newsletter();
            $newsletter->setEmail($faker->email);
            $manager->persist($newsletter);

            //Post
            for($j = 0; $j < 5; $j++) {
                $post = new Post();
                $post->setDate($faker->dateTime($max = 'now', $timezone = null));
                $post->setContent($faker->paragraph);
                $post->setTitle($faker->text($max=20));
                $post->setStatus($faker->text($max=10));
                $post->setName($faker->text($max=20));
                $post->setType($faker->mimeType);
                $post->setCategory($faker->text);
                $post->setAuthor($user);
                $manager->persist($post);

                //Comment
                for($k = 0; $k < 2; $k++) {
                    $comment = new Comment();
                    $comment->setName($faker->name);
                    $comment->setEmail($faker->email);
                    $comment->setContent($faker->paragraph);
                    $comment->setDate($faker->dateTime($max = 'now', $timezone = null));
                    $comment->setPost($post);
                    $manager->persist($comment);
                }
            }

        }

        $manager->flush();
    }
}
