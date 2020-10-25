# This script is intended to assist when upgrading a database from the old
# country region-based tax scheme to the new geographical tax zones.

#DROP TABLE zones_to_geo_zones;
#DROP TABLE geo_zones;

CREATE TABLE zones_to_geo_zones (
    association_id  INT(5)   NOT NULL AUTO_INCREMENT,
    zone_country_id INT(5)   NOT NULL,
    zone_id         INT(5)   NULL,
    geo_zone_id     INT(5)   NULL,
    last_modified   DATETIME NULL,
    date_added      DATETIME NOT NULL,
    PRIMARY KEY (association_id)
);

CREATE TABLE geo_zones (
    geo_zone_id          INT(5)       NOT NULL AUTO_INCREMENT,
    geo_zone_name        VARCHAR(32)  NOT NULL,
    geo_zone_description VARCHAR(255) NOT NULL,
    last_modified        DATETIME     NULL,
    date_added           DATETIME     NOT NULL,
    PRIMARY KEY (geo_zone_id)
);
ALTER TABLE tax_rates
    ADD COLUMN tax_priority INT(5) NOT NULL DEFAULT 1 AFTER tax_class_id;


INSERT INTO geo_zones (geo_zone_id, geo_zone_name, geo_zone_description, last_modified, date_added)
SELECT tr.tax_zone_id, zone_name, zone_name, NULL, now()
  FROM tax_rates tr, zones z, countries c
 WHERE tr.tax_zone_id = z.zone_id AND c.countries_id = z.zone_country_id
 GROUP BY tr.tax_zone_id;

INSERT INTO zones_to_geo_zones (zone_country_id, zone_id, geo_zone_id, date_added)
SELECT z.zone_country_id, z.zone_id, tr.tax_zone_id, now()
  FROM tax_rates tr, zones z
 WHERE z.zone_id = tr.tax_zone_id
 GROUP BY tr.tax_zone_id;
