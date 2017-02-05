<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $data = array();
        $form = $this->createFormBuilder($data)
        ->add('repository', ChoiceType::class,
            array('choices' => array(
                'repo1'   => 'repo1',
                'repo2' => 'repo2',
                'repo3'   => 'repo3',
            )))
        ->add('nameFile', TextType::class)
        ->add('textContent', TextareaType::class)
        ->add('overwrite', CheckboxType::class,
                array(
                    'label'    => 'Voulez-vous écraser le fichier existant ?',
                    'required' => false,
                ))
        ->getForm();

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            $data = $form->getData();
            $newFileName = sys_get_temp_dir().'/repos/'.$data['repository'].'/'.$data['nameFile'];
            $newFileContent = $data['textContent'];

            if($data['overwrite']){
                file_put_contents($newFileName, $newFileContent);
                echo "File created (" . basename($newFileName) . ")";
            }else{
                if(file_exists($newFileName)){
                    echo 'File exists. Change NameFile';
                }else{
                    file_put_contents($newFileName, $newFileContent);
                    echo "File created (" . basename($newFileName) . ")";
                }
            }
        }

        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
            'form' => $form->createView(),
        ]);
    }
}
