CREATE TABLE IF NOT EXISTS simfi_blog_entry (
  rowid bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  titel varchar(250) NOT NULL,
  content text NOT NULL,
  template text NOT NULL,
  images text,
  images_big text,
  tags text,
  created datetime NOT NULL,
  last_update datetime NOT NULL,
  author varchar(250) NOT NULL,
  active smallint default 0,
  UNIQUE KEY rowid (rowid)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS simfi_gallery_entry (
  rowid bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  g_key varchar(20),
  img_name text,
  img_name_big text,
  title varchar(250),
  content text,
  tags text,
  created datetime NOT NULL,
  last_update datetime NOT NULL,
  author varchar(250) NOT NULL,
  active smallint default 0,
  UNIQUE KEY rowid (rowid)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS simfi_contact_request (
  rowid bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  surname varchar(250),
  lastname varchar(250),
  salutation char(1),
  company varchar(250),
  street varchar(250),
  street_num varchar(5),
  city varchar(250),
  postalcode varchar(10),
  country varchar(100),
  telefon varchar(100),
  email varchar(250),
  comment text,
  send_copy char(1),
  ip varchar(20),
  created date NOT NULL,
  UNIQUE KEY rowid (rowid)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS simfi_pages (
  rowid bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  title varchar(250),
  page_key varchar(250),
  meta_tags text,
  meta_description text,
  template varchar(250),
  created date NOT NULL,
  last_update date NOT NULL,
  UNIQUE KEY rowid (rowid)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;