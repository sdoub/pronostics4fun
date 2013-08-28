<?php

/*
 *  Copyright (c) Nicholas Mossor Rathmann <nicholas.rathmann@gmail.com> 2008. All Rights Reserved.
 *
 *
 *  This file is part of OBBLM.
 *
 *  OBBLM is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  OBBLM is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

class Cup
{
  /* Tournament bracket structure. */

  protected $bracket = array(
  /*
   0 => array(),   // Play-in round
   */
  1 => array(),   // First round
  /*
   N => array(     // Round N

   0 => array( // Match 0
   'c1' => Competitor 1
   's1' => Competitor 1 score
   'c2' => Competitor 2
   's2' => Competitor 2 score
   ),
   1 => array( // Match 1
   ...
   ),
   n => array( // Match n
   ...
   ),
   ),
   */
  );

  public $roundsInfo = array(); // Rounds description array. This is the return value of getRoundsInfo().

  private $competitors = 0;
  public function __construct(array $compets)
  {
    /* Constructor creates initial bracket structure. */
    $this->competitors = count($compets);
    // At least 2 competitors are required.
    if (($n = count($compets)) < 2)
      return false;

    // In round 1, there are $r1 competitors. The number of competitors must match 2^x, where x is a whole number.
    //$r1 = pow(2, floor(log($n, 2)));
    $totalCompets = $n;
    $numberOfMatches = 0;
    switch ($n){
      case ($n> 8 && $n<= 16):
        $totalCompets = 16;
        break;
      case ($n> 16 && $n<= 32):
        $totalCompets = 32;
        break;
      case ($n> 32 && $n<= 64):
        $totalCompets = 64;
        break;
      case ($n> 64 && $n<= 128):
        $totalCompets = 128;
        break;
      default: //default
        $totalCompets = $n;
        break;
    }

    shuffle($compets);
    // extract home competitors
    $homeCompets =array();
    while (count($homeCompets)<$totalCompets/2) {
      $homeCompets[]=array_shift($compets);
    }

    $awayCompets =array();
    while (count($awayCompets)<$totalCompets/2) {
      if (count($compets)>0)
        $awayCompets[]=array_shift($compets);
      else
        $awayCompets[]=-1;

    }
    shuffle($awayCompets);

    // Create all matches for the first round.
    while (count($homeCompets) > 0) {
      $c1 = array_shift($homeCompets);
      $c2 = array_shift($awayCompets);
      array_push($this->bracket[1], array(
                'c1' => $c1,
                's1' => 0,
                'c2' => $c2,
                's2' => 0,
      ));
    }

    // Store rounds info.
    $this->roundsInfo = $this->getRoundsInfo();
  }

  public function setResByMatch($m, $r, $s1, $s2)
  {
    /* Sets a match result by specifying match number and round number. */

    // Test if input is valid.
    if (!$this->isMatchCreated($m, $r) ||                                               // Valid round and match?
    $this->bracket[$r][$m]['s1'] == -1 || $this->bracket[$r][$m]['s2'] == -1 ||     // Are competitors "ready"/exist?
    !is_int($s1) || !is_int($s2) || $s1 < 0 || $s2 < 0 || $s1 == $s2) {             // Valid scores?
      return false;
    }

    // Insert data.
    $this->bracket[$r][$m]['s1'] = $s1;
    $this->bracket[$r][$m]['s2'] = $s2;

    // Was the updated match the final? If yes, noting is left to be done. Note though, that round 0, the play-in round, is allowed to have one match only!
    if ($r !== 0 && $this->roundsInfo[$r][1] == 2) // Is n = 2 ? where n is the number of players in the round.
    return true;

    /* Update the match(es) in the following round(s). */

    list($nm, $nc) = $this->getNextMatch($m);
    $nr = $r+1;

    // Was match $m in round $r not already played?
    if (!$this->isMatchCreated($nm, $nr) || $this->bracket[$nr][$nm]['s'.$nc] == -1) {

      // Place winner of match in next match in next round.
      $this->bracket[$nr][$nm]['c'.$nc] = ($s1 > $s2) ? $this->bracket[$r][$m]['c1'] : $this->bracket[$r][$m]['c2']; // $s1 and $s2 are never equal due to input testing.
      $this->bracket[$nr][$nm]['s'.$nc] = 0;

      // If the match does not already exist, then the second competitor is missing. If so, we create an "undecided" marker by setting the score = -1.
      if (!array_key_exists('c' . ($ncs = ($nc == 1) ? 2 : 1), $this->bracket[$nr][$nm])) { // $ncs = Next match competitor (second).
        $this->bracket[$nr][$nm]['c'.$ncs] = null;
        $this->bracket[$nr][$nm]['s'.$ncs] = -1;
      }
    }
    else {

      /*
       Match $m in round $r had already been played...
       Now, if the new match result changes the match winner, we must update all match winners from match $m in round $r and on.
       */
      for (; $this->isMatchCreated($nm, $nr); $r++, $nr++, $m = $nm, list($nm, $nc) = $this->getNextMatch($m)){
        $this->bracket[$nr][$nm]['c'.$nc] = ($this->bracket[$r][$m]['s1'] > $this->bracket[$r][$m]['s2'])
        ? $this->bracket[$r][$m]['c1']
        : $this->bracket[$r][$m]['c2'];
      }
    }

    return true;
  }

  public function setResByCompets($c1, $c2, $s1, $s2)
  {
    /* Sets a match result by specifying the match competitors. */

    $match = $round = 0;
    $swap = $foundIt = false;

    foreach ($this->bracket as $r_idx => $r) {
      foreach ($r as $m_idx => $m) {
        if ($m['c1'] === $c1 && $m['c2'] === $c2 || $m['c1'] === $c2 && $m['c2'] === $c1 && $swap = true) {
          if ($swap) {
            $tmp = $s1;
            $s1 = $s2;
            $s2 = $tmp;
          }
          $match = $m_idx;
          $round = $r_idx;
          $foundIt = true;
          break;
        }
      }
    }

    if ($foundIt && $this->setResByMatch($match, $round, $s1, $s2))
    return true;
    else
    return false;
  }

  protected function getRoundsInfo()
  {
    /*
     The bracket structure alone is somewhat insufficient regarding the details of each round.
     This method returns an array of arrays which further describe each round.
     The elements of the out-most array are ordered, so that the index of each element corresponds to the round which the element describes.
     Each element is an array itself, and is structured like this: [0] = "round name", [1] = number of competitors in the round.

     For example:

     array(
     0 => array('Play-in round', 2),
     1 => array('Semi-finals', 4),
     2 => array('Final', 2),
     )
     */

    $rounds = array();

    for ($r = 1, $n = count($this->bracket[1])*2; $n > 1; $r++, $n /= 2) {
      switch ($n)
      {
        case 16: $name = 'Round of 16'; break;
        case 8:  $name = 'Quarter-finals'; break;
        case 4:  $name = 'Semi-finals'; break;
        case 2:  $name = 'Final'; break;
        default: $name = "Round $r"; break;
      }

      array_push($rounds, array($name, $n));
    }

    // Bump up all array indicies so that they fit round numbers.
    array_unshift($rounds, array()); // Temporary placeholder.
    unset($rounds[0]);

    // If play-in round exists, we add it now.
    if (!empty($this->bracket[0]))
    array_unshift($rounds, array('Play-in round', count($this->bracket[0])*2));

    return $rounds;
  }

  public function getNextMatch($m)
  {
    /* For a match number, $m, in round r, the winner is to compete in round r+1 in match $nm (next match) as competitor number $nc (next competitor). */

    /*
     Round r       Round r+1

     Match 0 -----
     |----- Match 0
     Match 1 -----

     Match 2 -----
     |----- Match 1
     Match 3 -----

     For each match in round r+1, it requires two match winners from round r.

     The competitors, 'c1' and 'c2', in match 0 in r+1, are:
     'c1' = winner of match 0 in r.
     'c2' = winner of match 1 in r.

     ... and so forth for match N in r+1.

     */

    $nm = (int) floor($m/2); // Next match.
    $nc = ($m % 2) ? 2 : 1; // Next competitor.

    return array($nm, $nc);
  }

  public function getPrevMatch($m, $c)
  {
    /* Does the reverse of getNextMatch(), so $m == getPrevMatch(getNextMatch($m)) */

    $pm = $m*2 + (($c == 2) ? 1 : 0);

    return $pm;
  }

  public function isMatchCreated($m, $r)
  {
    /* Tests if a specific match entry exists. */

    if (array_key_exists($r, $this->bracket) && array_key_exists($m, $this->bracket[$r]))
    return true;
    else
    return false;
  }

  public function isMatchPlayed($m, $r)
  {
    /* Tests if a specific match has been played. */

    if ($this->isMatchCreated($m, $r)) {
      $m = $this->bracket[$r][$m];
      if ($m['s1'] >= 0 && $m['s2'] >= 0 && $m['s1'] !== $m['s2'])
      return true;
    }

    return false;
  }

  public function renameCompets(array $dictionary) {

    /* Re-names competitors throughout the bracket. */

    foreach ($this->bracket as $r_idx => $matches) {
      foreach ($matches as $m_idx => $m) {
        $c1 = $this->bracket[$r_idx][$m_idx]['c1'];
        $c2 = $this->bracket[$r_idx][$m_idx]['c2'];
        $this->bracket[$r_idx][$m_idx]['c1'] = ($c1) ? $dictionary[$c1] : $c1;
        $this->bracket[$r_idx][$m_idx]['c2'] = ($c2) ? $dictionary[$c2] : $c2;
      }
    }

    return true;
  }

  public function getBracket()
  {
    return $this->bracket;
  }

  public function setBracket($bracket)
  {
    $this->bracket= $bracket;
  }


  public function ToArray()
  {
    $arr = array();
    $arr["Competitors"] = $this->competitors;
    $arr["Rounds"] = $this->roundsInfo;
    $arr["bracket"] = $this->bracket;
    return $arr;
  }
}
?>