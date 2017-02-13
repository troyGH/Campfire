/*USER
--add user Pete*/
INSERT INTO user ( user_fname, user_lname, user_email, user_password)
VALUES ('Peter','Curtis', 'pmjcurtis@gmail.com', '202cb962ac59075b964b07152d234b70');

/*add user Luis*/
INSERT INTO user ( user_fname, user_lname, user_email, user_password)
VALUES ('Luis','Otero', 'luisote94@gmail.com', '202cb962ac59075b964b07152d234b70');

/*ORGANIZATION*/
/*add baseball organization*/
INSERT INTO organization (org_title,org_description)
VALUES ('baseball','baseball for fun');

/*add movie organization*/
INSERT INTO organization (org_title,org_description)
VALUES ('movies','movies for fun');

/*BASEBALL*/
/*connect Pete to baseball organization*/
SET @u_id = SELECT user_id  FROM    user         WHERE user_email =  'pmjcurtis@gmail.com';
SET @o_id = SELECT org_id   FROM    organization WHERE org_title =   'baseball';

INSERT INTO owner (user_id, org_id)
VALUES (@u_id, @o_id);

/*connect Luis to baseball organization*/
@u_id = SELECT user_id FROM user WHERE user_email = 'luisote94@gmail.com';

INSERT INTO member (user_id, org_id)
VALUES (@u_id, @o_id);

/*MOVIES*/
/*connect Luis to movies organization*/

@o_id = SELECT org_id FROM organization WHERE org_title = 'movies';
INSERT INTO owner (user_id, org_id)
VALUES (@u_id, @o_id)

/*connect Pete to movies organization*/
@u_id = SELECT user_id FROM user WHERE user_email = 'pmjcurtis@gmail.com';
INSERT INTO member (user_id, org_id)
VALUES (@u_id, @o_id)

--connect Pete to movies organization
@u_id = SELECT user_id FROM user WHERE user_email = 'pmjcurtis@gmail.com';
INSERT INTO member (user_id, org_id)
VALUES (@u_id, @o_id)






