<?php
    namespace App\Controller;

    use App\Entity\Product;

    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\Routing\Annotation\Route;

    use Symfony\Component\Form\Extension\Core\Type\TextType;
    use Symfony\Component\Form\Extension\Core\Type\TextareaType;
    use Vich\UploaderBundle\Form\Type\VichImageType;
    use Symfony\Component\Form\Extension\Core\Type\SubmitType;
    use App\Form\Type\TagsTextType;

    class ProductController extends AbstractController {

        /**
         * @Route("/", name="home")
         */
        public function index() {
            return $this->redirectToRoute('product_list');
        }

        /**
         * @Route("/product", name="product_home")
         */
        public function product() {
            return $this->redirectToRoute('product_list');
        }

        /**
         * @Route("/product/list", methods={"GET"}, name="product_list")
         */
        public function list() {
            $products = $this->getDoctrine()->getRepository(Product::class)->findBy([], ['tsCreate' => 'ASC']);
            return $this->render('products/list.html.twig', ['products' => $products]);
        }

        /**
         * @Route("/product/create", methods={"GET","POST"}, name="product_create")
         */
        public function create(Request $request) {
            $product = new Product();

            $form = $this->createFormBuilder($product)
                ->add('name', TextType::class, [
                    'attr' => ['class' => 'form-control']
                ])
                ->add('description', TextareaType::class, [
                    'required' => false,
                    'attr' => ['class'=>'form-control']
                ])
                ->add('tagsText', TagsTextType::class)
                ->add('imageFile', VichImageType::class, [
                    'required' => false,
                    'attr' => ['class'=>'form-control']
                ])
                ->add('save', SubmitType::class, [
                    'label' => 'Create',
                    'attr' => ['class' => 'btn btn-primary mt-3']
                ])
            ->getForm();

            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $product = $form->getData();
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($product);
                $entityManager->flush();

                return $this->redirectToRoute('product_list');
            }

            return $this->render('products/create.html.twig', ['form' => $form->createView()]);
        }

        /**
         * @Route("/product/{id}/edit", methods={"GET","POST"}, name="product_edit")
         */
        public function edit(Request $request, $id) {
            $product = new Product();
            $product = $this->getDoctrine()->getRepository(Product::class)->find($id);

            $form = $this->createFormBuilder($product)
                ->add('name', TextType::class, [
                    'attr' => ['class' => 'form-control']
                ])
                ->add('description', TextareaType::class, [
                    'required' => false,
                    'attr' => ['class'=>'form-control']
                ])
                ->add('tagsText', TagsTextType::class, [])
                ->add('imageFile', VichImageType::class, [
                    'required' => false,
                    'allow_delete' => true,
                    'download_uri' => false,
                    'image_uri' => false,
                    'attr' => ['class'=>'form-control']
                ])
                ->add('save', SubmitType::class, [
                    'label' => 'Update',
                    'attr' => ['class' => 'btn btn-primary mt-3']
                ])
            ->getForm();

            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->flush();

                return $this->redirectToRoute('product_list');
            }

            return $this->render('products/edit.html.twig', ['form' => $form->createView()]);
        }

        /**
         * @Route("/product/{id}", name="product_show")
         */
        public function show($id) {
            $product = $this->getDoctrine()->getRepository(Product::class)->find($id);
            return $this->render('products/show.html.twig', ['product' => $product]);
        }

        /**
         * @Route("/product/delete/{id}", methods={"DELETE"}, name="product_delete")
         */
        public function delete(Request $request, $id) {
            $product = $this->getDoctrine()->getRepository(Product::class)->find($id);
            $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($product);
                $entityManager->flush();
            $response = new Response();
            $response->send();
        }
    }