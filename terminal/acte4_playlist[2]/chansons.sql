BEGIN TRANSACTION;
CREATE TABLE IF NOT EXISTS "chansons" (
	"ISRC"	TEXT NOT NULL,
	"titre"	TEXT,
	"artiste"	TEXT,
	"duree"	TEXT,
	"img"	TEXT,
	PRIMARY KEY("ISRC")
);
INSERT INTO "chansons" VALUES ('FRAB50712345
','volutes','Alain Bashung','3:40
','FRAB0712345.gif

');
COMMIT;
