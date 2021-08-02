<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Evaluation;
class Evaluationform extends Fixture
{
    public function load(ObjectManager $manager)
    {
         $product = new Evaluation();
         $product->setId(1);
         $product->setAddstatus('inactive');
         $product->setQ1('Begins and ends class according to planned time');
         $product->setQ2('issues and fully covers the course content');
         $product->setQ3('Conducts lectures according to planned timetable');
         $product->setQ4('Teaches and answers questions clearly');
         $product->setQ5('Encourages students to ask questions and participate in class');
         $product->setQ6('Conducts Continuous Assessment(CA) according to academic calendar and provides feedback before semester examination');
         $product->setQ7('Provides relevant guidance on CA so you know how you are doing and what you need to work on');
         $product->setQ8('Is available for consultation');
         $product->setQ9('Is fair in marking and grading');
         $manager->persist($product);

        $manager->flush();
    }
}
