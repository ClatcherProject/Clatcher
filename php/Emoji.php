<?php
class Emoji {
    public static function proceedText(string $text) {
        $poststext = preg_replace("/\n/", "<br>", $text);
        $poststext = preg_replace("/:laughing:/", " &#128512; ", $poststext);
        $poststext = preg_replace("/:grinning:/", " &#128513; ", $poststext);
        $poststext = preg_replace("/:tears:/", " &#128514; ", $poststext);
        $poststext = preg_replace("/:sweat:/", " &#128517; ", $poststext);
        $poststext = preg_replace("/:halo:/", " &#128519; ", $poststext);
        $poststext = preg_replace("/:devil:/", " &#128520; ", $poststext);
        $poststext = preg_replace("/:wink:/", " &#128521; ", $poststext);
        $poststext = preg_replace("/:tounge:/", " &#128523; ", $poststext);
        $poststext = preg_replace("/:hearts:/", " &#128525; ", $poststext);
        $poststext = preg_replace("/:cool:/", " &#128526; ", $poststext);
        $poststext = preg_replace("/:smirk:/", " &#128527; ", $poststext);
        $poststext = preg_replace("/:neutral:/", " &#128528; ", $poststext);
        $poststext = preg_replace("/:expressionless:/", " &#128529; ", $poststext);
        $poststext = preg_replace("/:unumused:/", " &#128530; ", $poststext);
        $poststext = preg_replace("/:coldsweat:/", " &#128531; ", $poststext);
        $poststext = preg_replace("/:confused:/", " &#128534; ", $poststext);
        $poststext = preg_replace("/:kiss:/", " &#128536; ", $poststext);
        $poststext = preg_replace("/:winktounge:/", " &#128540; ", $poststext);
        $poststext = preg_replace("/:disappointed:/", " &#128542; ", $poststext);
        $poststext = preg_replace("/:worried:/", " &#128543; ", $poststext);
        $poststext = preg_replace("/:angry:/", " &#128544; ", $poststext);
        $poststext = preg_replace("/:crying:/", " &#128546; ", $poststext);
        $poststext = preg_replace("/:triumph:/", " &#128548; ", $poststext);
        $poststext = preg_replace("/:tired:/", " &#128555; ", $poststext);
        $poststext = preg_replace("/:grimacing:/", " &#128556; ", $poststext);
        $poststext = preg_replace("/:loudlycrying:/", " &#128557; ", $poststext);
        $poststext = preg_replace("/:fear:/", " &#128561; ", $poststext);
        $poststext = preg_replace("/:clown:/", " &#129313; ", $poststext);
        $poststext = preg_replace("/:sick:/", " &#129314; ", $poststext);
        $poststext = preg_replace("/:honk:/", " &#129326; ", $poststext);
        $poststext = preg_replace("/:wtf:/", " &#129327; ", $poststext);

        return $poststext;
    }
}
?>