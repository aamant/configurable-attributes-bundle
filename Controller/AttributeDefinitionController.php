<?php

namespace Aamant\ConfigurableAttributesBundle\Controller;

//use Aamant\ConfigurableAttributesBundle\AamantConfigurableAttributesBundleEvent;
//use Aamant\ConfigurableAttributesBundle\Entity\AttributeDefinition;
//use Aamant\ConfigurableAttributesBundle\Entity\Option;
//use Aamant\ConfigurableAttributesBundle\Event\AttributesOptionChangedEvent;
//use Aamant\ConfigurableAttributesBundle\Form\AttributDefinitionEditType;
//use Aamant\ConfigurableAttributesBundle\Form\AttributeDefinitionForm;
//use Aamant\ConfigurableAttributesBundle\Form\AttributeDefinitionType;
//use Aamant\ConfigurableAttributesBundle\Form\AttributEditType;
use Aamant\ConfigurableAttributesBundle\Entity\AttributeDefinition;
use Aamant\ConfigurableAttributesBundle\Entity\Option;
use Aamant\ConfigurableAttributesBundle\Event\AttributesOptionChangedEvent;
use Aamant\ConfigurableAttributesBundle\Event\Events;
use Aamant\ConfigurableAttributesBundle\Form\AttributDefinitionEditType;
use Aamant\ConfigurableAttributesBundle\Form\AttributeDefinitionForm;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Attribute controller.
 *
 */
class AttributeDefinitionController extends Controller
{
    /**
     * Lists all Attribute entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $attributes = $em->getRepository('AamantConfigurableAttributesBundle:AttributeDefinition')->findAll();

        return $this->render('AamantConfigurableAttributesBundle:AttributeDefinition:index.html.twig', array(
            'attributes' => $attributes,
        ));
    }

    /**
     * Creates a new AttributeDefinition entity.
     *
     */
    public function newAction(Request $request)
    {
        $attribute = new AttributeDefinition();
        $attribute->addOption(new Option());

        $form = $this->createForm(AttributeDefinitionForm::class, $attribute);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            /** @var Option $option */
            foreach ($attribute->getOptions() as $option){
                $option->setAttributeDefinition($attribute);
            }
            $em->persist($attribute);
            $em->flush();

            return $this->redirectToRoute('attribute_show', array('id' => $attribute->getId()));
        }

        return $this->render('AamantConfigurableAttributesBundle:AttributeDefinition:new-edit.html.twig', array(
            'attribute' => $attribute,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Attribute entity.
     *
     */
    public function showAction(AttributeDefinition $attribute)
    {
        $deleteForm = $this->createDeleteForm($attribute);

        return $this->render('AamantConfigurableAttributesBundle:AttributeDefinition:show.html.twig', array(
            'attribute' => $attribute,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Attribute entity.
     *
     */
    public function editAction(Request $request, AttributeDefinition $attribute)
    {
        $deleteForm = $this->createDeleteForm($attribute);
        $editForm = $this->createForm(AttributeDefinitionForm::class, $attribute);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {

            try {
                $em = $this->getDoctrine()->getManager();

                $em->persist($attribute);

                $uow = $em->getUnitOfWork();
                $uow->computeChangeSets();
                $changedOptions = [];
                foreach ($attribute->getOptions() as $option){
                    $changed = $uow->getEntityChangeSet($option);
                    if (count($changed)) $changedOptions[] = $option;
                }

                $em->flush();

                foreach ($changedOptions as $option){
                    $this->get('event_dispatcher')->dispatch(
                        Events::ATTRIBUTE_OPTION_CHANGE, new AttributesOptionChangedEvent($option)
                    );
                }
            }
            catch (ForeignKeyConstraintViolationException $e){
                $this->addFlash('warning', "This option is used. You can not delete");
            }

            return $this->redirectToRoute('attribute_edit', array('id' => $attribute->getId()));
        }

        return $this->render('AamantConfigurableAttributesBundle:AttributeDefinition:new-edit.html.twig', array(
            'attribute' => $attribute,
            'form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Attribute entity.
     *
     */
    public function deleteAction(Request $request, AttributeDefinition $attribute)
    {
        $form = $this->createDeleteForm($attribute);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($attribute);
            $em->flush();
        }

        return $this->redirectToRoute('attribute_index');
    }

    /**
     * @param AttributeDefinition $attribute
     * @return \Symfony\Component\Form\Form
     */
    private function createDeleteForm(AttributeDefinition $attribute)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('attribute_delete', array('id' => $attribute->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
