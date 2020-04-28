<?php

function e($x) {
    return \htmlspecialchars($x);
}

function give_render(String $file, array $params = []) {
    $templates = new \League\Plates\Engine(dirname(__DIR__) . '/templates/');
    return $templates->render($file, $params);
}

function look_up_param(String $query, String $param, $fallback = null) {

    try {
        // if query doesn't have parameters
        if (\strpos($query, '?') === false) {
            return $fallback;

        // if query has parameters
        } else {
            [$_, $parameters] = explode('?', $query);

            // if we have more than one parameter (at least one & is present)
            if (\strpos($parameters, '&') !== false) {
                $params_array = explode('&', $parameters);

                foreach ($params_array as $par) {
                    // we loop through the code to check if a parameter has "redirect" as key
                    [$key, $value] = explode("=", $par);

                    if ($key == $param) {
                        return $value;
                    }
                }
                // redirect to fallback
                if (!$save) throw new \Exception;
            
            } else {
                [$key, $value] = explode("=", $parameters);

                // redirect to fallback
                if ($key != $param) throw new \Exception;

                return $value;
            }
        }
    } catch (\Exception $e) {
        return $fallback;
    }

}

function reconstruct_path_from_redirect_param($query, String $fallback_path, bool $no_params_on_fallback = false) : array {

    // if there are no params
    if (\strpos($query, '?') === false) return [$fallback_path, false];

    $res = look_up_param($query, "redirect");

    $response = $res !== null ? $res : $fallback_path;
    $had_to_fallback = ($res === null);

    if ($res === null && $no_params_on_fallback) {
        return [$response, $had_to_fallback];
    }

    // explode query
    [$_, $params] = explode('?', $query);

    // explode params
    $params = explode('&', $params);

    // delete redirect param if it exists
    if ($res !== null) unset($params[\array_search('redirect', $params)]);

    return [$response . '?' . implode('&', $params), $had_to_fallback];

}