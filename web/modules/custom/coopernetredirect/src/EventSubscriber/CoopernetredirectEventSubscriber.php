<?php
namespace Drupal\coopernetredirect\EventSubscriber;
use Drupal\Core\Url;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class CoopernetredirectEventSubscriber implements EventSubscriberInterface {

  public static function getSubscribedEvents() {
    $events = [];
    $events[KernelEvents::REQUEST][] = array('checkForCustomRedirect');
    return $events;
  }

  public function checkForCustomRedirect(GetResponseEvent $event) {
    $request = $event->getRequest();

    if($request->attributes->get('_route') == "view.events.list_event" ) {
      // test if path finishes with a number
      $current_path = \Drupal::service('path.current')->getPath();
      $r = preg_match_all("/.*?(\d+)$/", $current_path, $matches);
      if($r == 0) {
        $event->setResponse(new RedirectResponse(Url::fromRoute('view.events.list_event')->toString() . "/" . date("Y") . date("m")), $status = 301, $headers);
      }
    }
    /* if($request->attributes->get('_route') == "view.events.list_event" ) {
      // Get current path without query parameters
      $current_path = \Drupal::service('path.current')->getPath();
      
      // Remove query string if present
      $path_without_query = strtok($current_path, '?');
      
      // Test if path (without query string) finishes with a number
      $r = preg_match_all("/.*?(\d+)$/", $path_without_query, $matches);
      
      if($r == 0) {
        // Get the query string from the original request
        $query = $request->getQueryString();
        
        // Build the new URL
        $base_url = Url::fromRoute('view.events.list_event')->toString() . "/" . date("Y") . date("m");
        
        // Append query string if it exists
        $redirect_url = $query ? $base_url . '?' . $query : $base_url;
        
        $event->setResponse(new RedirectResponse($redirect_url, $status = 301, $headers));
      }
    } */
    if($request->attributes->get('_route') == "entity.user.canonical" ) {
      // to do : test if user has the right role
      $current_user = \Drupal::currentUser();
      //$userrole = $current_user->getRoles();

      // to do : test if user is just logged
      $event->setResponse(new RedirectResponse("/admin/content/moderated"), $status = 301, $headers);
    }
  }
}