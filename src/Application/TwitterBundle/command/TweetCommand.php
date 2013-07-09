<?php

namespace Application\TwitterBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class TweetCommand extends ContainerAwareCommand
{
	
	protected $name;
	 
	protected $output;
	
    protected function configure()
    {
        $this
            ->setName('twitter:update')
            ->setDescription('Grab tweets from twitter')
            ->addArgument('name', InputArgument::REQUIRED, 'Grab tweets from user')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->name = $input->getArgument('name');
		$this->output = $output;
		
		$this->getFeeds();
    }
	
	protected function getFeeds() {

		$this->output->writeln('<comment>Connecting to service.....</comment>');
		
		$data = 'https://api.twitter.com/1/statuses/user_timeline.rss?screen_name=' . $user . '&count=5'; 
		$feed = file_get_contents($data);
		
		$xmlarray = json_decode(json_encode((array) simplexml_load_string($feed)), 1);
		
		 return buildFeed($xmlarray['channel']['item']); // array
	}
}