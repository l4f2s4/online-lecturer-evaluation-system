<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;
use App\Entity\EvaluateMark;


class ChartController extends AbstractController
{
    /**
     * @Route("/evaluate/admin/chart/index", name="chart_index")
     */
   public function index(ChartBuilderInterface $chartBuilder): Response
    {
        $em=$this->getDoctrine()->getManager();
        $connection=$em->getConnection();
        $labels = [];
        $datasets = [];
         $decl1="select avg(round(avg)) as avg,program.pname as name from evaluate_mark inner join evaluate_mark_program on evaluate_mark.id=evaluate_mark_program.evaluate_mark_id
           inner join program on evaluate_mark_program.program_id=program.id group by program.pname order by avg desc";

        $statement121=$connection->prepare($decl1);
        $statement121->execute();
        $repo=$statement121->fetchAll();
        foreach($repo as $data){
            $labels[] = $data['name'];
            $datasets[] = $data['avg'];
        }
        $chart = $chartBuilder->createChart(Chart::TYPE_BAR);
        $chart->setData([
            'labels' => $labels,
            'datasets' => [
                [
                    'responsive'=> true,
                    'label' => 'rating scales',
                    'backgroundColor' =>  'rgba(54, 162, 235, 0.2)',
                    'borderColor' => 'purple',
                    'fontColor'=>"#80b6f4",
                    'pointBackgroundColor'=> "#80b6f4",
                    'pointHoverBackgroundColor'=> "#80b6f4",
                    'pointHoverBorderColor'=> "#80b6f4",
                    'pointBorderWidth'=> 10,
                    'pointHoverRadius'=> 10,
                    'pointHoverBorderWidth'=> 1,
                    'pointRadius'=> 3,
                    'fill'=> false,
                    'borderWidth'=> 4,
                    'data' => $datasets,
                ],
            ],
        ]);

        $chart->setOptions([
         'animation'=>[
        'tension'=> [
        'duration'=>1000,
        'easing'=> 'linear',
        'from'=> 1,
        'to'=> 0,
        'loop'=> true,
      ]
        ],
        'legend'=>[
            'position'=>"bottom",

        ],
            'scales' => [
                'yAxes' => [
                    ['ticks' => ['min' => 0, 'max' => 5,'beginAtZero'=>true,
                    'fontColor'=> "white",
                    'fontStyle'=> "normal",]
                    ],
                ],
                'xAxes' => [
                    ['ticks' => ['beginAtZero'=>true,
                    'fontColor'=> "white",
                    'fontStyle'=> "normal",]],
                ],
            ],
        ]);
        return $this->render('chart/index.html.twig', [
            'controller_name' => 'ChartController','chart'=>$chart
        ]);
    }

     /**
     * @Route("/hods/chart/index/hods", name="chart")
     */
   public function hut(ChartBuilderInterface $chartBuilder): Response
    {
       $user1=$this->getUser();
           if($user1 != null)
        {
           $userid=$user1->getId();
        $em=$this->getDoctrine()->getManager();
        $connection=$em->getConnection();
           //symfony sql statement to print student details
        $decl="SELECT department.id
        FROM `user` inner join department on user.userdept_id=department.id  where user.id=:part";

        $statement12=$connection->prepare($decl);
        $statement12->bindParam(':part',$userid);
        $statement12->execute();
        $findd=$statement12->fetchOne();
        $labels = [];
        $datasets = [];
         $decl1="select avg(round(avg)) as avg,program.pname as name
          from evaluate_mark inner join evaluate_mark_program
          on evaluate_mark.id=evaluate_mark_program.evaluate_mark_id
        inner join program on evaluate_mark_program.program_id=program.id
        inner join course_program on program.id=course_program.program_id
        inner join course on course_program.course_id=course.id
        inner join department on course.id=department.courseassigned_id
        where department.id=:ik group by program.pname order by avg desc";

        $statement121=$connection->prepare($decl1);
        $statement121->bindParam(':ik',$findd);
        $statement121->execute();
        $repo=$statement121->fetchAll();
        foreach($repo as $data){
            $labels[] = $data['name'];
            $datasets[] = $data['avg'];
        }
        $chart = $chartBuilder->createChart(Chart::TYPE_BAR);
        $chart->setData([
            'labels' => $labels,
            'datasets' => [
                [
                    'responsive'=> true,
                    'label' => 'rating scales',
                    'backgroundColor' =>  'rgba(54, 162, 235, 0.2)',
                    'borderColor' => 'purple',
                    'fontColor'=>"#80b6f4",
                    'pointBackgroundColor'=> "#80b6f4",
                    'pointHoverBackgroundColor'=> "#80b6f4",
                    'pointHoverBorderColor'=> "#80b6f4",
                    'pointBorderWidth'=> 10,
                    'pointHoverRadius'=> 10,
                    'pointHoverBorderWidth'=> 1,
                    'pointRadius'=> 3,
                    'fill'=> false,
                    'borderWidth'=> 4,
                    'data' => $datasets,
                ],
            ],
        ]);

        $chart->setOptions([
         'animation'=>[
        'tension'=> [
        'duration'=>1000,
        'easing'=> 'linear',
        'from'=> 1,
        'to'=> 0,
        'loop'=> true,
      ]
        ],
        'legend'=>[
            'position'=>"bottom",

        ],
            'scales' => [
                'yAxes' => [
                    ['ticks' => ['min' => 0, 'max' => 5,'beginAtZero'=>true,
                    'fontColor'=> "white",
                    'fontStyle'=> "normal",]
                    ],
                ],
                'xAxes' => [
                    ['ticks' => ['beginAtZero'=>true,
                    'fontColor'=> "white",
                    'fontStyle'=> "normal",]],
                ],
            ],
        ]);
        return $this->render('chart/index.html.twig', [
            'controller_name' => 'ChartController','chart'=>$chart
        ]);
        }
             else
         {
                    return $this->redirectToRoute('app_login');
          }
    }
}
