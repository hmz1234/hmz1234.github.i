<?php
if(!defined('__TYPECHO_ROOT_DIR__'))exit;

class Mirages_Action extends Typecho_Widget {
    private $routeMapping = array();

    public function execute(){
        $this->routeMapping['comment-avatar'] = function ($commentId, $ext) {
            $size = 100;
            if (strpos($commentId, "_") !== FALSE) {
                $split = explode("_", $commentId, 2);
                if (count($split) == 2) {
                    $commentId = $split[0];
                    $size = $split[1];
                }
            }
            $commentId = intval($commentId);
            $size = intval($size);

            if ($commentId <= 0) {
                $this->response->setStatus(404);
            }
            if ($size <= 0) {
                $size = 100;
            }

            $sizeMap = array(
                "140" => 4,
                "100" => 4,
                "40" => 3,
            );

            $imgType = 1;
            foreach ($sizeMap as $key => $type) {
                if ($size > intval($key)) {
                    $imgType = $type;
                    break;
                }
            }

            $db = Typecho_Db::get();
            $comment = $db->fetchRow($db->select('table.comments.mail')
                ->from('table.comments')
                ->where('table.comments.coid = ?', $commentId)
                ->limit(1));
            if (empty($comment)) {
                $this->response->setStatus(404);
                return;
            }
            $mail = $comment['mail'];
            $avatar = NULL;
            if (preg_match('/^(\d+)@qq.com$/i', $mail, $match)) {
                $qq = $match[1];

                $response = Mirages_Utils::httpRequestRaw("https://ptlogin2.qq.com/getface", "GET", array("imgtype" => $imgType, "uin" => $qq));
                if (strpos($response, "\"http") !== FALSE) {
                    $response = substr($response, strpos($response, "\"http") + 1);
                    $avatar = substr($response, 0, strpos($response, "\""));
                }
            }
            if (empty($avatar)) {
                $options = Helper::options();
                $avatar = Typecho_Common::gravatarUrl($mail, $size, $options->commentsAvatarRating, $options->defaultGravatar, true);
            }

            echo json_encode(array("url" => $avatar));
        };
    }


    public function dispatch() {
        $action = $this->request->get('action');
        $pathInfo = $this->request->get('pathInfo');

        if (array_key_exists($action, $this->routeMapping)) {
            $func = $this->routeMapping[$action];

            if (is_callable($func)) {
                if ($this->rejectReferer()) {
                    $this->response->setStatus(403);
                    return;
                }

                @list($path, $ext) = explode(".", $pathInfo, 2);
                call_user_func($func, $path, $ext);

                return;
            }
        }

        $this->response->setStatus(404);
    }

    private function rejectReferer() {
        return isset($_SERVER['HTTP_REFERER']) && parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST) !== $_SERVER['HTTP_HOST'];
    }
}
