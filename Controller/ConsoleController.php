<?php

namespace CoreSphere\ConsoleBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\StringInput;

use CoreSphere\ConsoleBundle\Output\StringOutput;

class ConsoleController extends Controller
{
    public function indexAction()
    {
        return $this->render('CoreSphereConsoleBundle:Console:index.html.twig', array(
            'working_dir' => getcwd()
        ));
    }

    public function execAction($_format = 'json')
    {
        chdir($this->container->getParameter('kernel.root_dir') . '/..');

        $request = $this->get('request');
        $command = $request->request->get('command');

        // TODO: CLEAN THIS UP

        if(preg_match('/cache:clear/', $command)) {
            $command .= ' --no-warmup';
        }

        // ----------

        $input = new StringInput($command);
        $output = new StringOutput();

        $env = $this;
        $debug = true;

        $kernel = $this->get('kernel');
        $application = new Application($kernel);
        $application->setAutoExit(FALSE);
        $application->run($input, $output);

        return $this->render('CoreSphereConsoleBundle:Console:result.' . $_format . '.twig', array(
            'input' => $command,
            'output' => $output->getOutput(),
        ));
    }
}
