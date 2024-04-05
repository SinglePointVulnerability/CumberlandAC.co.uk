DELIMITER $$
CREATE FUNCTION `topXPoints_division_split`(`input_runnerID` INT, `input_raceCode` INT, `input_champYear` INT, `input_topX` INT) RETURNS int(3)
BEGIN

DECLARE topXPoints INT;
SET @counter = 0;

SET topXPoints = ( SELECT SUM(innerQuery.racepoints)
FROM (
	SELECT rt.RunnerID
		,rac.ChampYear
		,rac.RaceCode
		,rt.RaceID
		,IF(rac.RaceDist = 16093, @counter:=@counter+1,0) AS 10miRaceCounter
		,rac.RaceDate
		,rt.RaceTime
		,(101 - rank_division_split(rt.RunnerID, rt.RaceID, rt.RaceTime)) AS racepoints
	FROM tblRaceTimes rt
	INNER JOIN tblRaces rac ON rt.RaceID = rac.RaceID
	WHERE rt.RunnerID = input_runnerID
		AND rac.RaceCode = input_raceCode
		AND rac.ChampYear = input_champYear
	ORDER BY (101 - rank_division_split(rt.RunnerID, rt.RaceID, rt.RaceTime)) DESC
		,rac.RaceDate ASC LIMIT input_topX
	) innerQuery
WHERE RunnerID = input_runnerID
AND RaceCode = input_raceCode
AND 10miRaceCounter < 3
);

RETURN topXPoints;
END$$
DELIMITER ;

-- 31/03/24: Added logic to ensure at least one of the three races in the Open (Long) category is a HM or longer 
-- - inner query; if race dist  = [10 miles] then increase counter by 1
-- - outer query; SUM... WHERE 10miRaceCounter < 3
-- 05/04/24: Corrected references in outer WHERE statement to input_runnerID and input_raceCode