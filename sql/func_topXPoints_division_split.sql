DELIMITER $$
CREATE DEFINER=`root`@`localhost` FUNCTION `topXPoints_division_split`(`input_runnerID` INT, `input_raceCode` INT, `input_champYear` INT, `input_topX` INT) RETURNS int(3)
BEGIN

DECLARE topXPoints INT;

SET topXPoints = ( SELECT SUM(innerQuery.racepoints)
FROM (
	SELECT rt.RunnerID
		,rac.ChampYear
		,rac.RaceCode
		,rt.RaceID
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
WHERE RunnerID = runnerid
AND RaceCode = racecode
);

RETURN topXPoints;
END$$
DELIMITER ;