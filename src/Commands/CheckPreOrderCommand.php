<?php
namespace App\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\EntityManagerInterface;

use App\Services\TwilioService;
/**
 * Set command for cron to run this command on once in an hour
 * Example: 0 * * * * /bin/console app:check-preorder
 */
class CheckPreOrderCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'app:check-preorder';
    
    private $entityManager;


    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
        
        parent::__construct();
    }
    
    protected function configure()
    {
        $this->setDescription("Bu komut 1 günden fazla bekleyen ön siparişleri askıya alır.");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $preorders = $this->entityManager->getRepository("App\Entity\PreOrder")->findBy(["status" => 'waiting']);
        $twilio_service = new TwilioService();
        foreach ($preorders as $preorder) {
            if($preorder->getDate()->getTimestamp() < strtotime("yesterday ".date("h:i:s")) ){
                $preorder->setStatus("autoRejected");
                $this->entityManager->persist($preorder);
                $this->entityManager->flush();
                $twilio_service->sendMessage($preorder->getId()." li ön siparişiniz sistem tarafından otomatik reddedildi.", 
                        $preorder->getUser()->getPhone());
            }
        }
        
    }
}

