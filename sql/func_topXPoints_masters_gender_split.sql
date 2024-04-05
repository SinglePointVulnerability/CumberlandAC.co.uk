DELIMITER $$
CREATE FUNCTION `topXPoints_masters_gender_split`(`input_runnerID` INT, `input_runnerSex` CHAR, `input_raceCode` INT, `input_champYear` INT, `input_topX` INT) RETURNS int(3)
BEGIN

DECLARE topXPoints INT;

SET topXPoints = (
SELECT SUM(innerQuery.racepoints)
FROM (
	SELECT rt.RunnerID
		,rac.ChampYear
		,rac.RaceCode
		,rt.RaceID
		,rt.RaceTime
		,(101 - rank_masters_gender_split(rt.RunnerID, input_runnerSex, rt.RaceID, rt.MastersRaceTime)) AS racepoints
	FROM tblRaceTimes rt
	INNER JOIN tblRaces rac ON rt.RaceID = rac.RaceID
	WHERE rt.RunnerID = input_runnerID
		AND rac.RaceCode = input_raceCode
		AND rac.ChampYear = input_champYear
		AND rt.RunnerAgeOnRaceDay >= 35
    	AND rt.MastersRaceTime IS NOT NULL
	ORDER BY (101 - rank_masters_gender_split(rt.RunnerID, input_runnerSex, rt.RaceID, rt.MastersRaceTime)) DESC
		,rac.RaceDate ASC LIMIT input_topX
	) innerQuery
WHERE RunnerID = input_runnerID
AND RaceCode = input_raceCode
);

RETURN topXPoints;
END$$
DELIMITER ;