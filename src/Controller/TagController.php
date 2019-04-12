<?php
    namespace App\Controller;

    use App\Entity\Tag;

    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\Routing\Annotation\Route;

    use Symfony\Component\Form\Extension\Core\Type\TextType;
    use Symfony\Component\Form\Extension\Core\Type\TextareaType;
    use Vich\UploaderBundle\Form\Type\VichImageType;
    use Symfony\Component\Form\Extension\Core\Type\SubmitType;
    use App\Form\Type\TagsTextType;

    class TagController extends AbstractController {
        /**
         * @Route("/tags/tags.json", name="tags", defaults={"_format": "json"})
         */
        public function tagsAction() {
            $tags = $this->getDoctrine()->getRepository('App:Tag')->findBy([], ['name' => 'ASC']);

            return $this->render('tags/tags.json.twig', ['tags' => $tags]);
        }
    }