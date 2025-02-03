<?php
   /*
   ** String will contain a pattern and a string seperated by a space. The
   ** function is to validate wether or not the string matches the pattern.
   ** The pattern follows the following rules a '+' indicates a lowercase
   ** alphabetic character from a-z. A '$' indicates a digit from 1 to 9. a '*'
   ** indicates any character, if it is followed by a number in brackets ie {6}
   ** the character should appear that many times. If no number is specified,
   ** the character should appear 3 times. If the pattern matches, return the
   ** string 'true' otherwise return the string 'false'.
   **
   ** Jim Barry - 20250122
   */
   function StringPatternMatch($str) {
      // Sepreate the two parts of the provided string into pattern and example
      $parts = explode(" ", $str, 2) ;
      // Set the return value to 'true'. If anything violates the pattern, it
      // will be switched to 'false'.
      $result = true ;

      // Make sure we have both parts
      if(count($parts) != 2) {
         return "false" ;
      }

      // Dump the pattern and the example into arrays of characters to allow
      // for quick comparisons
      $pattern = str_split($parts[0],1) ;
      $example = str_split($parts[1],1) ;

      // Set the current position in both arrays
      $pPos = 0 ;
      $ePos = 0 ;
      
      // Get the max index for each array
      $pCount = count($pattern) ;
      $eCount = count($example) ;

      // Placeholder for the regex we will construct.
      $RegexPattern = "" ;
      // Place holder for the number part of the '*' pasttern
      $cntstr = "" ;
      // The actual integer either defaulted to 3 or derived from $cntstr
      $cnt = 3 ;

      // Loop through the pattern and build a regex to test for it.
      while($pPos < count($pattern)) {
         if($pattern[$pPos] == "+") {
            // If it's a '+', add a single a-z character to the regex
            $RegexPattern .= "[a-z]";
            $pPos++ ;
            $ePos++ ;
         } elseif ($pattern[$pPos] == "$") {
            // If it's a '$', add a single 1-9 digit to the regex
            $RegexPattern .= "[1-9]";
            $pPos++ ;
            $ePos++ ;
         }
         elseif ($pattern[$pPos] == "*") {
            // if it's a '*', record the character and then determin the number
            // of appearances it should make
            $cntstr = "" ;
            $cnt = 3 ;
            // Look for the opening {
            if($pPos + 1 < $pCount && $pattern[$pPos + 1] == "{") {
               // Increment the position in the pattern
               $pPos++ ;
               // Collect the digits that make up the number
               while($pPos + 1 < $pCount && $pattern[$pPos] != "}") {
                  // Increment the position in the pattern
                  $pPos++ ;
                  if(inRange($pattern[$pPos],"0","9")) {
                     $cntstr .= $pattern[$pPos] ;
                  }
               }
               // Extract the digit as an int
               $cnt = intval($cntstr) ;
               if($cnt < 1) {
                  $cnt = 3 ;
               }
            }

            // Add the character and the counter to the regex
            if(isset($example[$ePos])) {
               $RegexPattern .= $example[$ePos] . "{" . $cnt . "}";
            } else {
               return "false" ;
            }

            // Increment the position in the pattern
            $pPos++ ;
            // Increment the position in the example
            $ePos += $cnt ;

         } else {
            // If there's some undefined character in the pattern, return false
            return "false";
         }

      }

      // Debug code
      // echo $RegexPattern . "\n" ;
      // echo $parts[1] . "\n" ;
      
      if(!preg_match("/" . $RegexPattern . "/",$parts[1])) {
         //If the reult does not match the string, return false
         $result = false ;
      }
      else {
         // If it does match  replace the matching portion with a null string
         // and see if there is a remainder then set the return value
         // accordingly.
         if(strlen(preg_replace("/" . $RegexPattern . "/", "", $parts[1])) < 1)
            $result = true ;
         else
            $result = false ;
      }

      // Assuming the result is set, return it here.
      if($result) {
         return "true" ;
      }

      // If somehow we've arrived here and result is unset, just return false
      return "false" ;
   }

   /*
   ** Checks if a value is within a range. Works for numberic or character
   ** types and returns a boolean
   **
   ** Jim Barry - 20250122
   */
   function inRange($val, $min, $max) {
      if($val >= $min && $val <= $max )
         return true ;
      else
         return false ;
   }
?>
