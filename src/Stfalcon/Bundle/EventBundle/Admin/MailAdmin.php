<?php
namespace Stfalcon\Bundle\EventBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;

use Knp\Bundle\MenuBundle\MenuItem;

class MailAdmin extends Admin
{
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('title')
            ->add('event')
        ;
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('General')
                ->add('title')
                ->add('text')
                ->add('event', 'entity',  array(
                    'class' => 'Stfalcon\Bundle\EventBundle\Entity\Event',
                    'multiple' => false, 'expanded' => false, 'required' => false
                ))
                ->add('start', null, array('required' => false))
                ->add('paymentStatus', 'choice', array(
                    'choices' => array('paid' => 'Оплачено', 'pending' => 'Не оплачено'),
                    'required' => false))
                ->end()
        ;
    }

    public function postUpdate($mail)
    {
        if (!$mail->getStart()) {
            return false;
        }

        // @todo refact
        if ($mail->getComplete()) {
            throw new \Exception('Эта рассылка уже разослана');
        }

        $container = $this->getConfigurationPool()->getContainer();
        $em = $container->get('doctrine')->getEntityManager();

        if ($mail->getEvent()) {
            // @todo сделать в репо метод для выборки пользователей, которые отметили ивент
            $tickets = $em->getRepository('StfalconEventBundle:Ticket')
                ->findBy(array('event' => $mail->getEvent()->getId()));

            foreach ($tickets as $ticket) {
                // @todo тяжелая цепочка
                // нужно сделать выборку билетов с платежами определенного статуса
                if($mail->getPaymentStatus()) {
                    if ($ticket->getPayment() && $ticket->getPayment()->getStatus() == $mail->getPaymentStatus()) {
                        $users[] = $ticket->getUser();
                    }
                } else {
                    $users[] = $ticket->getUser();
                }
            }
        } else {
            $users = $em->getRepository('ApplicationUserBundle:User')->findAll();
        }

        $mailer          = $container->get('mailer');
        $twig            = $container->get('twig');
        $templateContent = $twig->loadTemplate('StfalconEventBundle::email.txt.twig');

        foreach ($users as $user) {
            if (!$user->isSubscribe() && !$mail->getPaymentStatus()) {
                continue;
            }

            $bodyData['text'] = $mail->replace(
                array(
                    '%fullname%' => $user->getFullname(),
                    '%user_id%' => $user->getId(),
                )
            );

            $bodyData['logo'] = $mail->getEvent()->getLogo();
            $bodyData['background_image'] = $mail->getEvent()->getBackgroundImage();
            $bodyData['user'] = $user->getFullname();

            $body = $templateContent->render($bodyData);

            $templateTest = $twig->loadTemplate('StfalconEventBundle::invitation.html.twig');
            $bodyTest = $templateTest->render(array());

            $file = 'uploads/documents/test.pdf';
            /** @var $snappy \Knp\Bundle\SnappyBundle\Snappy\LoggableGenerator */
            $snappy = $container->get('knp_snappy.pdf');

            $snappy->generateFromHtml($bodyTest, $file, array(
                    'margin-bottom' => 0,
                    'margin-left' => 0,
                    'margin-right' => 0,
                    'margin-top' => 0,
                    //'lowquality' => false,
                    'page-size' => 'A4',
                    'orientation'=>'Landscape',
                    //'images' => true,
                    //'image-dpi' => 600,
                ), true);

            $message = \Swift_Message::newInstance()
                ->setSubject($mail->getTitle())
                // @todo refact
                ->setFrom('orgs@fwdays.com', 'Frameworks Days')
                ->setTo($user->getEmail())
                ->setBody($body, 'text/html');

            // @todo каждый вызов отнимает память
            $mailer->send($message);
        }

        $mail->setComplete(true);

        $em->persist($mail);
        $em->flush();

        return true;
    }

}