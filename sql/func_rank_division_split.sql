DELIMITER $$
CREATE FUNCTION `rank_division_split`(`input_runnerID` INT, `input_raceID` INT, `input_raceTime` TIME) RETURNS int(3)
BEGIN
	DECLARE raceRank INT;
	DECLARE runnerDivision INT;

	SET runnerDivision = (
			SELECT run.RunnerDiv
			FROM tblRunners run
			WHERE run.RunnerID = input_runnerID
			);
	SET raceRank = 1 + (
			SELECT COUNT(*)
			FROM tblRaceTimes rt
			INNER JOIN tblRunners run ON rt.RunnerID = run.RunnerID
			WHERE rt.RaceID = input_raceid
				AND run.RunnerDiv = runnerDivision
				AND rt.RaceTime < input_raceTime
			);

	RETURN raceRank;
END$$
DELIMITER ;