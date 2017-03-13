<?php
/**
 * Created by PhpStorm.
 * User: nursultan
 * Date: 3/13/17
 * Time: 6:55 PM
 */

namespace AppBundle\Controller\API;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;


/**
 * @Route("/api/environment")
 */
class EnvironmentController extends ApiController
{
    /**
     * @Route("/variables")
     * @Method("GET")
     * @ApiDoc(
     *     section="Environment",
     *     resource=true,
     *     description="Returns environment variables"
     * )
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function variablesAction(){
        return $this->prepareJsonResponse(array(
           'BASE_URL'=>$this->getParameter('base_url'),
            'BASE_URL_API'=>$this->getParameter('base_url_api')
        ));
    }
}