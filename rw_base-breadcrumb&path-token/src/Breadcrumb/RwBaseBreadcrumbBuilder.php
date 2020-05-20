<?php

namespace Drupal\rw_base\Breadcrumb;

use Drupal\Core\Breadcrumb\Breadcrumb;
use Drupal\Core\Breadcrumb\BreadcrumbBuilderInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Link;
use Drupal\taxonomy\Entity\Term;

class RwBaseBreadcrumbBuilder implements BreadcrumbBuilderInterface {
   /**
    * {@inheritdoc}
    */
   public function applies(RouteMatchInterface $attributes) {
       $parameters = $attributes->getParameters()->all();

       if (!empty($parameters['node'])) {
            $type = $parameters['node']->getType();
            $allowed_types = [
                'issue',
                'article',
                'author',
            ];
            return in_array($type, $allowed_types);
        }
    }

   /**
    * {@inheritdoc}
    */
   public function build(RouteMatchInterface $route_match) {
        $breadcrumb = new Breadcrumb();
        $breadcrumb->addLink(Link::createFromRoute('Home', '<front>'));
        $node = $route_match->getParameter('node');
        $node_type = $node->bundle();

        switch ($node_type) {
            case 'issue':
                $breadcrumb->addLink(Link::createFromRoute('All Issues', 'view.issues.list_page'));
                $last_title = $this->getIssueBreadcrumbTitle($node);
                break;

            case 'article':
                $is_blog = $node->get('field_web_only')->value;

                if ($is_blog) {
                    $breadcrumb->addLink(Link::createFromRoute('Blog', 'view.articles.blog_listing_page'));
                } else {
                    // Adding term reference to breadcrumb
                    /** @var \Drupal\taxonomy\Entity\Term $term */
                    // $term = $node->get('field_tag_department')->entity;
                    // $breadcrumb->addLink(Link::createFromRoute($term->getName(), 'entity.taxonomy_term.canonical', ['taxonomy_term' => $term->id()]));
                    $breadcrumb->addLink(Link::createFromRoute('All Issues', 'view.issues.list_page'));
                    $issue_node = $node->get('field_issue')->entity;
                    if (!empty($issue_node)) {
                        $breadcrumb->addLink(Link::createFromRoute($this->getIssueBreadcrumbTitle($issue_node), 'entity.node.canonical', ['node' => $issue_node->id()]));
                    }
                }

                $last_title = $this->truncateBreadcrumbText($node->getTitle());

                break;

            case 'author':
                $breadcrumb->addLink(Link::createFromRoute('All Authors', 'view.authors.authors_page'));
                $last_title = $node->getTitle();

                break;
        }

        $breadcrumb->addLink(Link::createFromRoute($last_title, '<none>'));

        // Ensure cache of breadcrumbs is handled properly
        $breadcrumb->addCacheContexts(['route']);

       return $breadcrumb;
   }

    /**
     * @param Text string
     */
    protected function truncateBreadcrumbText($text) {
        if (strlen($text) > 25) {
            $text = substr($text, 0, 25);
            return $text . '...';
        }
        return $text;
    }

    /**
     * @param Issue Node object
     */
    protected function getIssueBreadcrumbTitle($issue_node) {
        /** @var \Drupal\taxonomy\Entity\Term $term */
        $term = $issue_node->get('field_tag_theme')->entity;
        $title = 'RW #' . $issue_node->get('field_issue_number')->value . ' (' . $term->getName() . ')';

        return $title;
    }
}