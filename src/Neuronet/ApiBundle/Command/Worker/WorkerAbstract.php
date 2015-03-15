<?php
namespace Neuronet\ApiBundle\Command\Worker;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class WorkerAbstract extends ContainerAwareCommand
{
    /**
     * If the worker needs to keep working this will stay true
     *
     * @var boolean
     */
    protected $keepWorking = true;

    /**
     * if the worker needs to shutdown immediately this will become true
     *
     * @var boolean
     */
    protected $shutdownNow = false;

    /**
     * If the worker needs to stop working after it is done
     *
     * @var boolean
     */
    protected $shutdownGracefully = false;
    
    /**
     * Set to true if the verbose messages should be sent to the output. Must be public ,used for childs as well
     *
     * @var boolean
     */
    protected $verbose;
    
    /**
     * @var OutputInterface
     */
    protected $output;

    /**
     * @var InputInterface
     */
    protected $input;

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output  = $output;
        $this->input   = $input;
        $this->verbose = $input->getOption('verbose');
        
         //make sure signals are respected
        declare(ticks = 1);
        pcntl_signal(SIGTERM, array($this, "sigTerm"));
        pcntl_signal(SIGINT, array($this, "sigInt"));
        
        $this->verboseOutput("<info>Starting up</info>");
        
        $this->onWorkerStart();
        
        while ( $this->keepWorking ) {
            $this->workerProcess();
            if ( $this->shutdownNow || $this->shutdownGracefully ) {
                $this->keepWorking = false;
            }
        }
        
        $this->onWorkerStop();
    }
    
    protected function onWorkerStart() { }
    protected function onWorkerStop() { }
    protected function workerProcess() 
    { 
        $this->shutdownNow = true; 
    }

    /**
     * Gracefully handle the signal to terminate as soon as possible
     *
     * @return void
     */
    public function sigTerm()
    {
        $this->shutdownGracefully = true;
        $this->verboseOutput("<info>Received Shutdown signal</info>");
    }

    /**
     * We're being told the process should shutdown now (ctrl+c)
     *
     * @return void
     */
    public function sigInt()
    {
        $this->shutdownNow = true;
        $this->shutdownGracefully = true;
        $this->verboseOutput("<info>Received Interrupt signal</info>");
    }

    /**
     * Outputs the string if verbose mode is on
     *
     * @param string $string
     * @return void
     */
    public function verboseOutput($string)
    {
        if ( $this->isVerbose() ) {
            $this->output->writeLn($string);
        }
    }
    
    /**
     * Returns true if there should be verbose output
     *
     * @return boolean
     */
    public function isVerbose()
    {
        return $this->verbose;
    }

}
