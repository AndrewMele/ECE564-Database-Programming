--
-- Initialize Database Script
--
CREATE DATABASE INF;
USE INF;

SELECT "Creating Tables";
CREATE TABLE Guilds
  ( 
    name                    VARCHAR(32) NOT NULL, 
    balance                 INT DEFAULT 0, 
    PRIMARY KEY(name)
  );
CREATE TABLE Inventory
 ( 
   name                     VARCHAR(32) NOT NULL,
   quantity                 SMALLINT DEFAULT 0,
   guild                    VARCHAR(32) NOT NULL,
   FOREIGN KEY(guild)       REFERENCES Guilds(name),
   PRIMARY KEY(guild,name)
 );

CREATE TABLE Events
 (
   guild                    VARCHAR(32) NOT NULL,
   name                     VARCHAR(32) NOT NULL,
   date                     DATE NOT NULL,
   time                     TIME NOT NULL,
   location                 VARCHAR(32) NOT NULL,
   FOREIGN KEY(guild)       REFERENCES Guilds(name),
   PRIMARY KEY(guild,name)
 );

CREATE TABLE GVG_Teams
 (
   guild                    VARCHAR(32) NOT NULL,
   rank                     CHAR(1) NOT NULL,
   tank                     VARCHAR(32) NOT NULL,
   solo_dps                 VARCHAR(32) NOT NULL,
   aoe_dps                  VARCHAR(32) NOT NULL,
   utility                  VARCHAR(32) NOT NULL,
   healer                   VARCHAR(32) NOT NULL,
   FOREIGN KEY(guild)       REFERENCES Guilds(name),
   PRIMARY KEY(guild,rank)
 );

CREATE TABLE Players
  ( username                VARCHAR(32) NOT NULL,
    password                CHAR(128) NOT NULL,  
    guild                   VARCHAR(32) NOT NULL, 
    role                    VARCHAR(32) DEFAULT 'inactive', 
    FOREIGN KEY(guild)      REFERENCES Guilds(name), 
    PRIMARY KEY(username)
  );

CREATE TABLE Crafting
 (
   player                   VARCHAR(32) NOT NULL,
   class                    VARCHAR(32) NOT NULL,
   type                     VARCHAR(32) NOT NULL,
   sub_type                 VARCHAR(32) NOT NULL,
   specialty                VARCHAR(32) NOT NULL,
   level                    TINYINT(3) NOT NULL,
   spc_level                TINYINT(3) NOT NULL,
   FOREIGN KEY(player)      REFERENCES Players(username),
   PRIMARY KEY(player,sub_type,specialty)
 );

CREATE TABLE Gathering
 (
   player                   VARCHAR(32) NOT NULL,
   resource                 VARCHAR(32) NOT NULL,
   tier                     TINYINT(1) DEFAULT 2,
   FOREIGN KEY(player)      REFERENCES Players(username),
   PRIMARY KEY(player,resource)
 );

CREATE TABLE Sets
 (
   player                   VARCHAR(32) NOT NULL,
   m_hand                   VARCHAR(32) NOT NULL,
   o_hand                   VARCHAR(32),
   head                     VARCHAR(32) NOT NULL,
   chest                    VARCHAR(32) NOT NULL,
   feet                     VARCHAR(32) NOT NULL,
   potion                   VARCHAR(32) NOT NULL,
   food                     VARCHAR(32) NOT NULL,
   mh_level                 TINYINT(3) NOT NULL,
   oh_level                 TINYINT(3),
   head_level               TINYINT(3) NOT NULL,
   chest_level              TINYINT(3) NOT NULL,
   feet_level               TINYINT(3) NOT NULL,
   FOREIGN KEY(player)      REFERENCES Players(username),
   PRIMARY KEY(player,m_hand)
 );

 CREATE TABLE Farming
  (
    player                  VARCHAR(32) NOT NULL,
    class                    VARCHAR(32) NOT NULL,
    type                   VARCHAR(32) NOT NULL,
    specialty               VARCHAR(32) NOT NULL,
    level                   TINYINT(3) NOT NULL,
    spc_level               TINYINT(3) NOT NULL,
    FOREIGN KEY(player)     REFERENCES Players(username),
    PRIMARY KEY(player,specialty)
  );

CREATE TABLE Islands
 (
   name                     VARCHAR(32) NOT NULL,
   caretaker                VARCHAR(32) NOT NULL,
   tier                     TINYINT(1) NOT NULL,
   FOREIGN KEY(caretaker)   REFERENCES Players(username),
   PRIMARY KEY(name)
 );

CREATE TABLE Plots
 (
   island                   VARCHAR(32) NOT NULL,
   location                 VARCHAR(32) NOT NULL,
   type                     VARCHAR(32) NOT NULL,
   FOREIGN KEY(island)      REFERENCES Islands(name),
   PRIMARY KEY(island,location)
 );


-- Create Secure User
SELECT "Creating Secure User";
CREATE USER 'sec_user'@'localhost' IDENTIFIED BY 'AWpUNcf7jhatNfnD';
GRANT SELECT, INSERT, UPDATE ON `inf`.* TO 'sec_user'@'localhost';

--
-- Add Test Data
--

-- Guilds
SELECT "Adding Guilds";
INSERT INTO Guilds(name) VALUES('Infamy Reborn');

-- Players
SELECT "Adding PLayers";
INSERT INTO Players(username,guild,role) VALUES('Telgin', 'Infamy Reborn', 'GM');
INSERT INTO Players(username,guild,role) VALUES('Senaki', 'Infamy Reborn', 'GMW');
INSERT INTO Players(username,guild,role) VALUES('Nilas', 'Infamy Reborn', 'Right Hand');
INSERT INTO Players(username,guild,role) VALUES('Lebra', 'Infamy Reborn', 'Right Hand');
INSERT INTO Players(username,guild,role) VALUES('Therow', 'Infamy Reborn', 'Officer');
INSERT INTO Players(username,guild,role) VALUES('SoundOne', 'Infamy Reborn', 'Officer');
INSERT INTO Players(username,guild,role) VALUES('Venomeese', 'Infamy Reborn', 'Warmaster');
INSERT INTO Players(username,guild,role) VALUES('Jaiyk', 'Infamy Reborn', 'Right Hand');
INSERT INTO Players(username,guild,role) VALUES('LustfulAcorn', 'Infamy Reborn', 'Warmaster');
INSERT INTO Players(username,guild,role) VALUES('Valikia', 'Infamy Reborn', 'Officer');

-- Gathering
SELECT "Adding Gathering";
INSERT INTO Gathering VALUES('Telgin', 'Ore Miner', 6);
INSERT INTO Gathering VALUES('Lebra', 'Ore Miner', 7);
INSERT INTO Gathering VALUES('Senaki', 'Fiber Harvester', 6);
INSERT INTO Gathering VALUES('Nilas', 'Ore Miner', 6);
INSERT INTO Gathering VALUES('Nilas', 'Lumberjack', 6);
INSERT INTO Gathering VALUES('Nilas', 'Animal Skinner', 6);
INSERT INTO Gathering VALUES('Nilas', 'Fiber Harvester', 6);
INSERT INTO Gathering VALUES('Nilas', 'Stone Quarrier', 6);
INSERT INTO Gathering VALUES('Therow', 'Lumberjack', 7);
INSERT INTO Gathering VALUES('SoundOne', 'Animal Skinner', 7);
INSERT INTO Gathering VALUES('Venomeese', 'Stone Quarrier', 4);
INSERT INTO Gathering VALUES('Venomeese', 'Ore Miner', 5);
INSERT INTO Gathering VALUES('Jaiyk', 'Fiber Harvester', 5);
INSERT INTO Gathering VALUES('Jaiyk', 'Lumberjack', 4);
INSERT INTO Gathering VALUES('LustfulAcorn', 'Stone Quarrier', 8);
INSERT INTO Gathering VALUES('LustfulAcorn', 'Ore Miner', 6);
INSERT INTO Gathering VALUES('Valikia', 'Fiber Harvester', 7);
INSERT INTO Gathering VALUES('Valikia', 'Lumberjack', 7);

-- Crafting
SELECT "Adding Crafting";
INSERT INTO Crafting VALUES('Telgin', 'Combat', 'Hunter', 'Spear', 'Glaive', 60, 58);
INSERT INTO Crafting VALUES('Telgin', 'Utility', 'Gathering', 'Refiner', 'Ore', 70, 72);
INSERT INTO Crafting VALUES('Lebra', 'Combat', 'Mage', 'Fire Staff', 'Great Fire Staff', 40, 25);
INSERT INTO Crafting VALUES('Lebra', 'Utility', 'Gathering', 'Gear', 'Pickaxe', 90, 78);
INSERT INTO Crafting VALUES('Senaki', 'Combat', 'Mage', 'Cloth Robe', 'Mage Robe', 75, 55);
INSERT INTO Crafting VALUES('Senaki', 'Combat', 'Mage', 'Cloth Cowl', 'Mage Cowl', 77, 56);
INSERT INTO Crafting VALUES('Senaki', 'Combat', 'Mage', 'Cloth Sandals', 'Mage Sandals', 82, 74);
INSERT INTO Crafting VALUES('Nilas', 'Utility', 'Gathering', 'Refiner', 'Ore', 12, 5);
INSERT INTO Crafting VALUES('Nilas', 'Utility', 'Gathering', 'Refiner', 'Wood', 35, 15);
INSERT INTO Crafting VALUES('Nilas', 'Utility', 'Gathering', 'Refiner', 'Stone', 78, 55);
INSERT INTO Crafting VALUES('Nilas', 'Utility', 'Gathering', 'Refiner', 'Fiber', 49, 35);
INSERT INTO Crafting VALUES('Nilas', 'Utility', 'Gathering', 'Refiner', 'Tanner', 100, 95);
INSERT INTO Crafting VALUES('Lebra', 'Combat', 'Warrior', 'Hammer', 'Great Hammer', 77, 68);
INSERT INTO Crafting VALUES('Lebra', 'Utility', 'Gathering', 'Refiner', 'Ore', 65, 45);
INSERT INTO Crafting VALUES('Therow', 'Combat', 'Hunter', 'Dagger', 'Claws', 100, 95);
INSERT INTO Crafting VALUES('Therow', 'Utility', 'Gathering', 'Refiner', 'Wood', 52, 35);
INSERT INTO Crafting VALUES('SoundOne', 'Combat', 'Mage', 'Frost Staff', 'Great Frost Staff', 80, 45);
INSERT INTO Crafting VALUES('SoundOne', 'Utility', 'Gathering', 'Gear', 'Skinning Knife', 60, 45);

-- Farming

INSERT INTO Farming VALUES('Senaki', 'Harvest', 'Crop', 'Potato', 60, 38);
INSERT INTO Farming VALUES('Senaki', 'Chef', 'Chef', 'Salad', 80, 76);
INSERT INTO Farming VALUES('Telgin', 'Harvest', 'Pasture', 'Horse', 55, 25);
INSERT INTO Farming VALUES('Nilas', 'Chef', 'Chef', 'Poison', 30, 20);
INSERT INTO Farming VALUES('SoundOne', 'Harvest', 'Herb', 'Foxglove', 100, 92);
INSERT INTO Farming VALUES('Therow', 'Harvest', 'Herb', 'Ghost', 42, 32);
INSERT INTO Farming VALUES('Lebra', 'Harvest', 'Crop', 'Cabbage', 10, 5);
INSERT INTO Farming VALUES('Valikia', 'Harvest', 'Crop', 'Wheat', 80, 76);



