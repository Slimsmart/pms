<?php

class GaleShapley {
    /**
     * Runs the classical Gale-Shapley (Deferred Acceptance) matching algorithm.
     * 
     * @param array $students List of student usernames
     * @param array $studentPrefs Associative array of [student_username => [staff_username_1, staff_username_2, ...]] ordered by preference (highest first)
     * @param array $staffPrefs Associative array of [staff_username => [student_username_1, student_username_2, ...]] ordered by preference (highest first)
     * @param array $capacities Associative array of [staff_username => capacity_integer]
     * @return array Associative array of [student_username => staff_username] representing final stable matches
     */
    public static function match($students, $studentPrefs, $staffPrefs, $capacities) {
        // Initialize matches: staff => list of matched students
        $matches = [];
        foreach ($capacities as $staff => $cap) {
            $matches[$staff] = [];
        }
        
        $freeStudents = $students;
        // Keep track of which staff index a student is proposing to next
        $proposalIndex = [];
        foreach ($students as $student) {
            $proposalIndex[$student] = 0;
        }
        
        // Helper comparator: returns true if staff prefers studentA over studentB
        $prefers = function($staff, $studentA, $studentB) use ($staffPrefs) {
            $prefs = isset($staffPrefs[$staff]) ? $staffPrefs[$staff] : [];
            $posA = array_search($studentA, $prefs);
            $posB = array_search($studentB, $prefs);
            
            // If both are ranked by the staff
            if ($posA !== false && $posB !== false) {
                return $posA < $posB;
            }
            // If only studentA is ranked
            if ($posA !== false) {
                return true;
            }
            // If only studentB is ranked
            if ($posB !== false) {
                return false;
            }
            // Fallback: stable lexical order to prevent infinite loops and ensure determinism
            return $studentA < $studentB;
        };
        
        // Loop while there are free students to process
        while (!empty($freeStudents)) {
            $student = array_shift($freeStudents);
            
            $prefs = isset($studentPrefs[$student]) ? $studentPrefs[$student] : [];
            $idx = $proposalIndex[$student];
            
            // If student has proposed to all options on their list, they remain unmatched
            if ($idx >= count($prefs)) {
                continue;
            }
            
            $staff = $prefs[$idx];
            $proposalIndex[$student]++;
            
            // If target staff is invalid or has no capacity defined, try next preference
            if (!isset($capacities[$staff])) {
                array_unshift($freeStudents, $student);
                continue;
            }
            
            $capacity = $capacities[$staff];
            $currentMatches = $matches[$staff];
            
            if (count($currentMatches) < $capacity) {
                // Staff has free capacity, accept proposal tentatively
                $matches[$staff][] = $student;
            } else {
                // Staff is full, find the least preferred student currently matched
                $leastPreferred = null;
                foreach ($currentMatches as $matchedStudent) {
                    if ($leastPreferred === null || $prefers($staff, $leastPreferred, $matchedStudent)) {
                        $leastPreferred = $matchedStudent;
                    }
                }
                
                // Compare proposing student with the least preferred matched student
                if ($prefers($staff, $student, $leastPreferred)) {
                    // Staff prefers the new student. Reject the least preferred matched student
                    $key = array_search($leastPreferred, $matches[$staff]);
                    if ($key !== false) {
                        unset($matches[$staff][$key]);
                        $matches[$staff] = array_values($matches[$staff]); // Re-index array
                    }
                    
                    // Match proposing student
                    $matches[$staff][] = $student;
                    
                    // Add rejected student back to the free list
                    $freeStudents[] = $leastPreferred;
                } else {
                    // Reject the proposing student, put them back to try their next preference
                    $freeStudents[] = $student;
                }
            }
        }
        
        // Format output as [student_username => staff_username]
        $finalMatching = [];
        foreach ($matches as $staff => $stds) {
            foreach ($stds as $std) {
                $finalMatching[$std] = $staff;
            }
        }
        
        return $finalMatching;
    }
}
