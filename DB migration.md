## Import old

CREATE DATABASE inventar CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER inventar@localhost IDENTIFIED BY 'inventar';
GRANT ALL ON inventar.* TO inventar@localhost;
use inventar;
source itverwalter_dump_d02f4778_2020_07_27_10_31.sql;


## Change old

RENAME TABLE `inventar`.`user` TO `inventar`.`user_alt`;

ALTER TABLE `inventar`.`company` CHANGE `created` `created_at` DATETIME NOT NULL;
ALTER TABLE `inventar`.`company` CHANGE `updated` `updated_at` DATETIME NULL DEFAULT NULL;

ALTER TABLE `inventar`.`customer` CHANGE `created` `created_at` DATETIME NOT NULL;
ALTER TABLE `inventar`.`customer` CHANGE `updated` `updated_at` DATETIME NULL DEFAULT NULL;

ALTER TABLE `inventar`.`customer_site` CHANGE `created` `created_at` DATETIME NOT NULL;
ALTER TABLE `inventar`.`customer_site` CHANGE `updated` `updated_at` DATETIME NULL DEFAULT NULL;

ALTER TABLE `inventar`.`document` CHANGE `created` `created_at` DATETIME NOT NULL;
ALTER TABLE `inventar`.`document` CHANGE `updated` `updated_at` DATETIME NULL DEFAULT NULL;

ALTER TABLE `inventar`.`inventory` CHANGE `created` `created_at` DATETIME NOT NULL;
ALTER TABLE `inventar`.`inventory` CHANGE `updated` `updated_at` DATETIME NULL DEFAULT NULL;

ALTER TABLE `inventar`.`public_holiday` CHANGE `created` `created_at` DATETIME NOT NULL;
ALTER TABLE `inventar`.`public_holiday` CHANGE `updated` `updated_at` DATETIME NULL DEFAULT NULL;

ALTER TABLE `inventar`.`purchase` CHANGE `created` `created_at` DATETIME NOT NULL;
ALTER TABLE `inventar`.`purchase` CHANGE `updated` `updated_at` DATETIME NULL DEFAULT NULL;

ALTER TABLE `inventar`.`purchase_customer` CHANGE `created` `created_at` DATETIME NOT NULL;
ALTER TABLE `inventar`.`purchase_customer` CHANGE `updated` `updated_at` DATETIME NULL DEFAULT NULL;

ALTER TABLE `inventar`.`purchase_staff` CHANGE `created` `created_at` DATETIME NOT NULL;
ALTER TABLE `inventar`.`purchase_staff` CHANGE `updated` `updated_at` DATETIME NULL DEFAULT NULL;

ALTER TABLE `inventar`.`staff` CHANGE `created` `created_at` DATETIME NOT NULL;
ALTER TABLE `inventar`.`staff` CHANGE `updated` `updated_at` DATETIME NULL DEFAULT NULL;

ALTER TABLE `inventar`.`staff_holiday` CHANGE `created` `created_at` DATETIME NOT NULL;
ALTER TABLE `inventar`.`staff_holiday` CHANGE `updated` `updated_at` DATETIME NULL DEFAULT NULL;

ALTER TABLE `inventar`.`staff_note` CHANGE `created` `created_at` DATETIME NOT NULL;
ALTER TABLE `inventar`.`staff_note` CHANGE `updated` `updated_at` DATETIME NULL DEFAULT NULL;

ALTER TABLE `inventar`.`staff_summary` CHANGE `created` `created_at` DATETIME NOT NULL;
ALTER TABLE `inventar`.`staff_summary` CHANGE `updated` `updated_at` DATETIME NULL DEFAULT NULL;

ALTER TABLE `inventar`.`supplier` CHANGE `created` `created_at` DATETIME NOT NULL;
ALTER TABLE `inventar`.`supplier` CHANGE `updated` `updated_at` DATETIME NULL DEFAULT NULL;

ALTER TABLE `inventar`.`tag` CHANGE `created` `created_at` DATETIME NOT NULL;
ALTER TABLE `inventar`.`tag` CHANGE `updated` `updated_at` DATETIME NULL DEFAULT NULL;

ALTER TABLE `inventar`.`ticket` CHANGE `created` `created_at` DATETIME NOT NULL;
ALTER TABLE `inventar`.`ticket` CHANGE `updated` `updated_at` DATETIME NULL DEFAULT NULL;

ALTER TABLE `inventar`.`ticket_note` CHANGE `created` `created_at` DATETIME NOT NULL;
ALTER TABLE `inventar`.`ticket_note` CHANGE `updated` `updated_at` DATETIME NULL DEFAULT NULL;

ALTER TABLE `inventar`.`time_correction` CHANGE `created` `created_at` DATETIME NOT NULL;
ALTER TABLE `inventar`.`time_correction` CHANGE `updated` `updated_at` DATETIME NULL DEFAULT NULL;

ALTER TABLE `inventar`.`time_entry` CHANGE `created` `created_at` DATETIME NOT NULL;
ALTER TABLE `inventar`.`time_entry` CHANGE `updated` `updated_at` DATETIME NULL DEFAULT NULL;

ALTER TABLE `inventar`.`user_alt` CHANGE `created` `created_at` DATETIME NOT NULL;
ALTER TABLE `inventar`.`user_alt` CHANGE `updated` `updated_at` DATETIME NULL DEFAULT NULL;


UPDATE `inventar`.`company` SET `created_at` = '2000-01-01 00:00:00' WHERE `created_at` = '0000-00-00 00:00:00';


## Create new
- sudo -u postgres psql
- CREATE DATABASE inventar;
- CREATE USER inventar WITH ENCRYPTED PASSWORD 'inventar';
- GRANT ALL PRIVILEGES ON DATABASE inventar to inventar;

## Migrate laravel
- php artisan migrate


## Copy old

sudo apt-get install pgloader
pgloader mysql://inventar:inventar@localhost/inventar postgresql://inventar:inventar@localhost/inventar


## Change new - as su
SET search_path TO inventar,public;

ALTER TABLE company SET SCHEMA public;
ALTER TABLE company ALTER COLUMN created_by_id TYPE bigint;
ALTER TABLE company ALTER COLUMN updated_by_id TYPE bigint;

ALTER TABLE customer SET SCHEMA public;
ALTER TABLE customer ALTER COLUMN created_by_id TYPE bigint;
ALTER TABLE customer ALTER COLUMN updated_by_id TYPE bigint;

ALTER TABLE customer_site SET SCHEMA public;
ALTER TABLE customer_site ALTER COLUMN created_by_id TYPE bigint;
ALTER TABLE customer_site ALTER COLUMN updated_by_id TYPE bigint;

ALTER TABLE document SET SCHEMA public;
ALTER TABLE document ALTER COLUMN created_by_id TYPE bigint;
ALTER TABLE document ALTER COLUMN updated_by_id TYPE bigint;

ALTER TABLE inventory SET SCHEMA public;
ALTER TABLE inventory ALTER COLUMN created_by_id TYPE bigint;
ALTER TABLE inventory ALTER COLUMN updated_by_id TYPE bigint;

ALTER TABLE public_holiday SET SCHEMA public;
ALTER TABLE public_holiday ALTER COLUMN created_by_id TYPE bigint;
ALTER TABLE public_holiday ALTER COLUMN updated_by_id TYPE bigint;

ALTER TABLE purchase SET SCHEMA public;
ALTER TABLE purchase ALTER COLUMN created_by_id TYPE bigint;
ALTER TABLE purchase ALTER COLUMN updated_by_id TYPE bigint;

ALTER TABLE purchase_customer SET SCHEMA public;
ALTER TABLE purchase_customer ALTER COLUMN created_by_id TYPE bigint;
ALTER TABLE purchase_customer ALTER COLUMN updated_by_id TYPE bigint;

ALTER TABLE purchase_staff SET SCHEMA public;
ALTER TABLE purchase_staff ALTER COLUMN created_by_id TYPE bigint;
ALTER TABLE purchase_staff ALTER COLUMN updated_by_id TYPE bigint;

ALTER TABLE staff SET SCHEMA public;
ALTER TABLE staff ALTER COLUMN created_by_id TYPE bigint;
ALTER TABLE staff ALTER COLUMN updated_by_id TYPE bigint;

ALTER TABLE staff_holiday SET SCHEMA public;
ALTER TABLE staff_holiday ALTER COLUMN created_by_id TYPE bigint;
ALTER TABLE staff_holiday ALTER COLUMN updated_by_id TYPE bigint;

ALTER TABLE staff_note SET SCHEMA public;
ALTER TABLE staff_note ALTER COLUMN created_by_id TYPE bigint;
ALTER TABLE staff_note ALTER COLUMN updated_by_id TYPE bigint;

ALTER TABLE staff_summary SET SCHEMA public;
ALTER TABLE staff_summary ALTER COLUMN created_by_id TYPE bigint;
ALTER TABLE staff_summary ALTER COLUMN updated_by_id TYPE bigint;

ALTER TABLE supplier SET SCHEMA public;
ALTER TABLE supplier ALTER COLUMN created_by_id TYPE bigint;
ALTER TABLE supplier ALTER COLUMN updated_by_id TYPE bigint;

ALTER TABLE tag SET SCHEMA public;
ALTER TABLE tag ALTER COLUMN created_by_id TYPE bigint;
ALTER TABLE tag ALTER COLUMN updated_by_id TYPE bigint;

ALTER TABLE ticket SET SCHEMA public;
ALTER TABLE ticket ALTER COLUMN created_by_id TYPE bigint;
ALTER TABLE ticket ALTER COLUMN updated_by_id TYPE bigint;

ALTER TABLE ticket_note SET SCHEMA public;
ALTER TABLE ticket_note ALTER COLUMN created_by_id TYPE bigint;
ALTER TABLE ticket_note ALTER COLUMN updated_by_id TYPE bigint;

ALTER TABLE time_correction SET SCHEMA public;
ALTER TABLE time_correction ALTER COLUMN created_by_id TYPE bigint;
ALTER TABLE time_correction ALTER COLUMN updated_by_id TYPE bigint;

ALTER TABLE time_entry SET SCHEMA public;
ALTER TABLE time_entry ALTER COLUMN created_by_id TYPE bigint;
ALTER TABLE time_entry ALTER COLUMN updated_by_id TYPE bigint;

ALTER TABLE user_alt SET SCHEMA public;
ALTER TABLE user_alt ALTER COLUMN created_by_id TYPE bigint;
ALTER TABLE user_alt ALTER COLUMN updated_by_id TYPE bigint;


ALTER TABLE document_customer SET SCHEMA public;
ALTER TABLE document_purchase SET SCHEMA public;
ALTER TABLE document_staff SET SCHEMA public;
ALTER TABLE document_tag SET SCHEMA public;

ALTER TYPE purchase_type SET SCHEMA public;
ALTER TYPE tag_type SET SCHEMA public;
ALTER TYPE ticket_priority SET SCHEMA public;
ALTER TYPE ticket_schedule_repetition SET SCHEMA public;
ALTER TYPE ticket_status SET SCHEMA public;
ALTER TYPE time_entry_entry_type SET SCHEMA public;

DROP SCHEMA inventar;


## Change new - as user

ALTER TABLE users
ADD COLUMN created_by_id bigint,
ADD COLUMN updated_by_id bigint,
ADD COLUMN role character varying(100) COLLATE pg_catalog."default" NOT NULL,
ADD COLUMN auth_phone character varying(100) COLLATE pg_catalog."default" NOT NULL,
ADD COLUMN uses2fa boolean NOT NULL,
ADD COLUMN auth_code character varying(10) COLLATE pg_catalog."default" NOT NULL,
ADD COLUMN active boolean NOT NULL,
ADD COLUMN backup_codes text COLLATE pg_catalog."default",
ADD COLUMN max_idle_time bigint NOT NULL,
ADD COLUMN staff_id bigint;


COMMENT ON COLUMN users.backup_codes
IS '(DC2Type:simple_array)';
-- Index: idx_18499_idx_8d93d649896dbbde

-- DROP INDEX public.idx_18499_idx_8d93d649896dbbde;

CREATE INDEX idx_18499_idx_8d93d649896dbbde
ON users USING btree
(updated_by_id ASC NULLS LAST)
TABLESPACE pg_default;
-- Index: idx_18499_idx_8d93d649b03a8386

-- DROP INDEX public.idx_18499_idx_8d93d649b03a8386;

CREATE INDEX idx_18499_idx_8d93d649b03a8386
ON users USING btree
(created_by_id ASC NULLS LAST)
TABLESPACE pg_default;
-- Index: idx_18499_uniq_8d93d649d4d57cd

-- DROP INDEX public.idx_18499_uniq_8d93d649d4d57cd;

CREATE UNIQUE INDEX idx_18499_uniq_8d93d649d4d57cd
ON users USING btree
(staff_id ASC NULLS LAST)
TABLESPACE pg_default;
-- Index: idx_18499_uniq_8d93d649e7927c74

-- DROP INDEX public.idx_18499_uniq_8d93d649e7927c74;

CREATE UNIQUE INDEX idx_18499_uniq_8d93d649e7927c74
ON users USING btree
(email COLLATE pg_catalog."default" ASC NULLS LAST)
TABLESPACE pg_default;








INSERT INTO users (id, name, email, password,  created_at, updated_at, created_by_id, updated_by_id, role, auth_phone, uses2fa, auth_code, active, backup_codes, max_idle_time, staff_id)
SELECT             id, id,   email, 'TO SET', created_at, updated_at, created_by_id, updated_by_id, role, auth_phone, uses2fa, auth_code, active, backup_codes, max_idle_time, staff_id
FROM user_alt
WHERE id > 1;


INSERT INTO users (id, name, email, email_verified_at, password, remember_token, created_at, updated_at, role, auth_phone, uses2fa, auth_code, active, max_idle_time)
VALUES (1, 'olly', 'olly@wittkopf.eu', null, '$2y$10$u.qL/MvRAg/.8dIYN4AeKeKrCHAd83mddnIUtfqhINLzGGSO8IQyy', null, '2020-07-27 13:33:37', '2020-07-27 13:33:37', 'TO DO', 'TO DO', false, 'TO DO', true, 0);


DROP TABLE user_alt;

ALTER TABLE company ADD CONSTRAINT FK_created_by_id FOREIGN KEY(created_by_id) REFERENCES users(id);
ALTER TABLE company ADD CONSTRAINT FK_updated_by_id FOREIGN KEY(updated_by_id) REFERENCES users(id);

ALTER TABLE customer ADD CONSTRAINT FK_created_by_id FOREIGN KEY(created_by_id) REFERENCES users(id);
ALTER TABLE customer ADD CONSTRAINT FK_updated_by_id FOREIGN KEY(updated_by_id) REFERENCES users(id);

ALTER TABLE customer_site ADD CONSTRAINT FK_created_by_id FOREIGN KEY(created_by_id) REFERENCES users(id);
ALTER TABLE customer_site  ADD CONSTRAINT FK_updated_by_id FOREIGN KEY(updated_by_id) REFERENCES users(id);

ALTER TABLE document ADD CONSTRAINT FK_created_by_id FOREIGN KEY(created_by_id) REFERENCES users(id);
ALTER TABLE document ADD CONSTRAINT FK_updated_by_id FOREIGN KEY(updated_by_id) REFERENCES users(id);

ALTER TABLE inventory ADD CONSTRAINT FK_created_by_id FOREIGN KEY(created_by_id) REFERENCES users(id);
ALTER TABLE inventory ADD CONSTRAINT FK_updated_by_id FOREIGN KEY(updated_by_id) REFERENCES users(id);

ALTER TABLE public_holiday ADD CONSTRAINT FK_created_by_id FOREIGN KEY(created_by_id) REFERENCES users(id);
ALTER TABLE public_holiday ADD CONSTRAINT FK_updated_by_id FOREIGN KEY(updated_by_id) REFERENCES users(id);

ALTER TABLE purchase ADD CONSTRAINT FK_created_by_id FOREIGN KEY(created_by_id) REFERENCES users(id);
ALTER TABLE purchase ADD CONSTRAINT FK_updated_by_id FOREIGN KEY(updated_by_id) REFERENCES users(id);

ALTER TABLE purchase_customer ADD CONSTRAINT FK_created_by_id FOREIGN KEY(created_by_id) REFERENCES users(id);
ALTER TABLE purchase_customer ADD CONSTRAINT FK_updated_by_id FOREIGN KEY(updated_by_id) REFERENCES users(id);

ALTER TABLE purchase_staff ADD CONSTRAINT FK_created_by_id FOREIGN KEY(created_by_id) REFERENCES users(id);
ALTER TABLE purchase_staff ADD CONSTRAINT FK_updated_by_id FOREIGN KEY(updated_by_id) REFERENCES users(id);

ALTER TABLE staff ADD CONSTRAINT FK_created_by_id FOREIGN KEY(created_by_id) REFERENCES users(id);
ALTER TABLE staff ADD CONSTRAINT FK_updated_by_id FOREIGN KEY(updated_by_id) REFERENCES users(id);

ALTER TABLE staff_holiday ADD CONSTRAINT FK_created_by_id FOREIGN KEY(created_by_id) REFERENCES users(id);
ALTER TABLE staff_holiday ADD CONSTRAINT FK_updated_by_id FOREIGN KEY(updated_by_id) REFERENCES users(id);

ALTER TABLE staff_note ADD CONSTRAINT FK_created_by_id FOREIGN KEY(created_by_id) REFERENCES users(id);
ALTER TABLE staff_note ADD CONSTRAINT FK_updated_by_id FOREIGN KEY(updated_by_id) REFERENCES users(id);

ALTER TABLE staff_summary ADD CONSTRAINT FK_created_by_id FOREIGN KEY(created_by_id) REFERENCES users(id);
ALTER TABLE staff_summary ADD CONSTRAINT FK_updated_by_id FOREIGN KEY(updated_by_id) REFERENCES users(id);

ALTER TABLE supplier ADD CONSTRAINT FK_created_by_id FOREIGN KEY(created_by_id) REFERENCES users(id);
ALTER TABLE supplier ADD CONSTRAINT FK_updated_by_id FOREIGN KEY(updated_by_id) REFERENCES users(id);

ALTER TABLE tag ADD CONSTRAINT FK_created_by_id FOREIGN KEY(created_by_id) REFERENCES users(id);
ALTER TABLE tag ADD CONSTRAINT FK_updated_by_id FOREIGN KEY(updated_by_id) REFERENCES users(id);

ALTER TABLE ticket ADD CONSTRAINT FK_created_by_id FOREIGN KEY(created_by_id) REFERENCES users(id);
ALTER TABLE ticket ADD CONSTRAINT FK_updated_by_id FOREIGN KEY(updated_by_id) REFERENCES users(id);

ALTER TABLE ticket_note ADD CONSTRAINT FK_created_by_id FOREIGN KEY(created_by_id) REFERENCES users(id);
ALTER TABLE ticket_note ADD CONSTRAINT FK_updated_by_id FOREIGN KEY(updated_by_id) REFERENCES users(id);

ALTER TABLE time_correction ADD CONSTRAINT FK_created_by_id FOREIGN KEY(created_by_id) REFERENCES users(id);
ALTER TABLE time_correction ADD CONSTRAINT FK_updated_by_id FOREIGN KEY(updated_by_id) REFERENCES users(id);

ALTER TABLE time_entry ADD CONSTRAINT FK_created_by_id FOREIGN KEY(created_by_id) REFERENCES users(id);
ALTER TABLE time_entry ADD CONSTRAINT FK_updated_by_id FOREIGN KEY(updated_by_id) REFERENCES users(id);

ALTER TABLE users ADD CONSTRAINT FK_created_by_id FOREIGN KEY(created_by_id) REFERENCES users(id);
ALTER TABLE users ADD CONSTRAINT FK_updated_by_id FOREIGN KEY(updated_by_id) REFERENCES users(id);


ALTER TABLE customer_site ALTER COLUMN customer_id TYPE bigint;
ALTER TABLE document ALTER COLUMN supplier_id TYPE bigint;
ALTER TABLE document ALTER COLUMN assigned_by_id TYPE bigint;
ALTER TABLE document_customer ALTER COLUMN customer_id TYPE bigint;
ALTER TABLE document_customer ALTER COLUMN document_id TYPE bigint;
ALTER TABLE document_purchase ALTER COLUMN purchase_id TYPE bigint;
ALTER TABLE document_purchase ALTER COLUMN document_id TYPE bigint;
ALTER TABLE document_staff ALTER COLUMN document_id TYPE bigint;
ALTER TABLE document_staff ALTER COLUMN staff_id TYPE bigint;
ALTER TABLE document_tag ALTER COLUMN tag_id TYPE bigint;
ALTER TABLE document_tag ALTER COLUMN document_id TYPE bigint;
ALTER TABLE inventory ALTER COLUMN supplier_id TYPE bigint;
ALTER TABLE inventory ALTER COLUMN purchase_id TYPE bigint;
ALTER TABLE inventory ALTER COLUMN billed_by_id TYPE bigint;
ALTER TABLE inventory ALTER COLUMN customer_id TYPE bigint;
ALTER TABLE inventory ALTER COLUMN staff_id TYPE bigint;
ALTER TABLE inventory ALTER COLUMN purchase_customer_id TYPE bigint;
ALTER TABLE inventory ALTER COLUMN customer_site_id TYPE bigint;
ALTER TABLE purchase ALTER COLUMN supplier_id TYPE bigint;
ALTER TABLE purchase ALTER COLUMN tag_id TYPE bigint;
ALTER TABLE purchase_customer ALTER COLUMN purchase_id TYPE bigint;
ALTER TABLE purchase_customer ALTER COLUMN billed_by_id TYPE bigint;
ALTER TABLE purchase_customer ALTER COLUMN customer_id TYPE bigint;
ALTER TABLE purchase_staff ALTER COLUMN purchase_id TYPE bigint;
ALTER TABLE purchase_staff ALTER COLUMN staff_id TYPE bigint;
ALTER TABLE staff_holiday ALTER COLUMN staff_id TYPE bigint;
ALTER TABLE staff_note ALTER COLUMN staff_id TYPE bigint;
ALTER TABLE staff_summary ALTER COLUMN staff_id TYPE bigint;
ALTER TABLE ticket ALTER COLUMN editor_staff_id TYPE bigint;
ALTER TABLE ticket ALTER COLUMN customer_id TYPE bigint;
ALTER TABLE ticket ALTER COLUMN inventory_id TYPE bigint;
ALTER TABLE ticket ALTER COLUMN customer_site_id TYPE bigint;
ALTER TABLE ticket_note ALTER COLUMN ticket_id TYPE bigint;
ALTER TABLE time_correction ALTER COLUMN staff_id TYPE bigint;
ALTER TABLE time_entry ALTER COLUMN staff_id TYPE bigint;
ALTER TABLE users ALTER COLUMN staff_id TYPE bigint;



ALTER TABLE customer_site ADD CONSTRAINT FK_customer_id FOREIGN KEY(customer_id) REFERENCES customer(id) ON UPDATE RESTRICT ON DELETE RESTRICT;
ALTER TABLE document ADD CONSTRAINT FK_supplier_id FOREIGN KEY(supplier_id) REFERENCES supplier(id) ON UPDATE RESTRICT ON DELETE RESTRICT;
ALTER TABLE document ADD CONSTRAINT FK_user_id FOREIGN KEY(assigned_by_id) REFERENCES users(id) ON UPDATE RESTRICT ON DELETE RESTRICT;
ALTER TABLE document_customer ADD CONSTRAINT FK_customer_id FOREIGN KEY(customer_id) REFERENCES customer(id) ON UPDATE RESTRICT ON DELETE CASCADE;
ALTER TABLE document_customer ADD CONSTRAINT FK_document_id FOREIGN KEY(document_id) REFERENCES document(id) ON UPDATE RESTRICT ON DELETE CASCADE;
ALTER TABLE document_purchase ADD CONSTRAINT FK_purchase_id FOREIGN KEY(purchase_id) REFERENCES purchase(id) ON UPDATE RESTRICT ON DELETE CASCADE;
ALTER TABLE document_purchase ADD CONSTRAINT FK_document_id FOREIGN KEY(document_id) REFERENCES document(id) ON UPDATE RESTRICT ON DELETE CASCADE;
ALTER TABLE document_staff ADD CONSTRAINT FK_document_id FOREIGN KEY(document_id) REFERENCES document(id) ON UPDATE RESTRICT ON DELETE CASCADE;
ALTER TABLE document_staff ADD CONSTRAINT FK_staff_id FOREIGN KEY(staff_id) REFERENCES staff(id) ON UPDATE RESTRICT ON DELETE CASCADE;
ALTER TABLE document_tag ADD CONSTRAINT FK_tag_id FOREIGN KEY(tag_id) REFERENCES tag(id) ON UPDATE RESTRICT ON DELETE CASCADE;
ALTER TABLE document_tag ADD CONSTRAINT FK_document_id FOREIGN KEY(document_id) REFERENCES document(id) ON UPDATE RESTRICT ON DELETE CASCADE;
ALTER TABLE inventory ADD CONSTRAINT FK_supplier_id FOREIGN KEY(supplier_id) REFERENCES supplier(id) ON UPDATE RESTRICT ON DELETE RESTRICT;
ALTER TABLE inventory ADD CONSTRAINT FK_purchase_id FOREIGN KEY(purchase_id) REFERENCES purchase(id) ON UPDATE RESTRICT ON DELETE RESTRICT;
ALTER TABLE inventory ADD CONSTRAINT FK_user_id FOREIGN KEY(billed_by_id) REFERENCES users(id) ON UPDATE RESTRICT ON DELETE RESTRICT;
ALTER TABLE inventory ADD CONSTRAINT FK_customer_id FOREIGN KEY(customer_id) REFERENCES customer(id) ON UPDATE RESTRICT ON DELETE RESTRICT;
ALTER TABLE inventory ADD CONSTRAINT FK_staff_id FOREIGN KEY(staff_id) REFERENCES staff(id) ON UPDATE RESTRICT ON DELETE RESTRICT;
ALTER TABLE inventory ADD CONSTRAINT FK_purchase_customer_id FOREIGN KEY(purchase_customer_id) REFERENCES purchase_customer(id) ON UPDATE RESTRICT ON DELETE RESTRICT;
ALTER TABLE inventory ADD CONSTRAINT FK_customer_site_id FOREIGN KEY(customer_site_id) REFERENCES customer_site(id) ON UPDATE RESTRICT ON DELETE RESTRICT;
ALTER TABLE purchase ADD CONSTRAINT FK_supplier_id FOREIGN KEY(supplier_id) REFERENCES supplier(id) ON UPDATE RESTRICT ON DELETE RESTRICT;
ALTER TABLE purchase ADD CONSTRAINT FK_tag_id FOREIGN KEY(tag_id) REFERENCES tag(id) ON UPDATE RESTRICT ON DELETE RESTRICT;
ALTER TABLE purchase_customer ADD CONSTRAINT FK_purchase_id FOREIGN KEY(purchase_id) REFERENCES purchase(id) ON UPDATE RESTRICT ON DELETE RESTRICT;
ALTER TABLE purchase_customer ADD CONSTRAINT FK_user_id FOREIGN KEY(billed_by_id) REFERENCES users(id) ON UPDATE RESTRICT ON DELETE RESTRICT;
ALTER TABLE purchase_customer ADD CONSTRAINT FK_customer_id FOREIGN KEY(customer_id) REFERENCES customer(id) ON UPDATE RESTRICT ON DELETE RESTRICT;
ALTER TABLE purchase_staff ADD CONSTRAINT FK_purchase_id FOREIGN KEY(purchase_id) REFERENCES purchase(id) ON UPDATE RESTRICT ON DELETE RESTRICT;
ALTER TABLE purchase_staff ADD CONSTRAINT FK_staff_id FOREIGN KEY(staff_id) REFERENCES staff(id) ON UPDATE RESTRICT ON DELETE RESTRICT;
ALTER TABLE staff_holiday ADD CONSTRAINT FK_staff_id FOREIGN KEY(staff_id) REFERENCES staff(id) ON UPDATE RESTRICT ON DELETE RESTRICT;
ALTER TABLE staff_note ADD CONSTRAINT FK_staff_id FOREIGN KEY(staff_id) REFERENCES staff(id) ON UPDATE RESTRICT ON DELETE RESTRICT;
ALTER TABLE staff_summary ADD CONSTRAINT FK_staff_id FOREIGN KEY(staff_id) REFERENCES staff(id) ON UPDATE RESTRICT ON DELETE RESTRICT;
ALTER TABLE ticket ADD CONSTRAINT FK_staff_id FOREIGN KEY(editor_staff_id) REFERENCES staff(id) ON UPDATE RESTRICT ON DELETE RESTRICT;
ALTER TABLE ticket ADD CONSTRAINT FK_customer_id FOREIGN KEY(customer_id) REFERENCES customer(id) ON UPDATE RESTRICT ON DELETE RESTRICT;
ALTER TABLE ticket ADD CONSTRAINT FK_inventory_id FOREIGN KEY(inventory_id) REFERENCES inventory(id) ON UPDATE RESTRICT ON DELETE RESTRICT;
ALTER TABLE ticket ADD CONSTRAINT FK_customer_site_id FOREIGN KEY(customer_site_id) REFERENCES customer_site(id) ON UPDATE RESTRICT ON DELETE RESTRICT;
ALTER TABLE ticket_note ADD CONSTRAINT FK_ticket_id FOREIGN KEY(ticket_id) REFERENCES ticket(id) ON UPDATE RESTRICT ON DELETE RESTRICT;
ALTER TABLE time_correction ADD CONSTRAINT FK_staff_id FOREIGN KEY(staff_id) REFERENCES staff(id) ON UPDATE RESTRICT ON DELETE RESTRICT;
ALTER TABLE time_entry ADD CONSTRAINT FK_staff_id FOREIGN KEY(staff_id) REFERENCES staff(id) ON UPDATE RESTRICT ON DELETE RESTRICT;
ALTER TABLE users ADD CONSTRAINT FK_staff_id FOREIGN KEY(staff_id) REFERENCES staff(id) ON UPDATE RESTRICT ON DELETE RESTRICT;

ALTER TABLE company RENAME TO companies;
ALTER TABLE customer RENAME TO customers;
ALTER TABLE customer_site RENAME TO customer_sites;
ALTER TABLE document RENAME TO documents;
ALTER TABLE inventory RENAME TO products;
ALTER TABLE public_holiday RENAME TO public_holidays;
ALTER TABLE purchase RENAME TO purchases;
ALTER TABLE purchase_customer RENAME TO customer_purchases;
ALTER TABLE purchase_staff RENAME TO staff_purchases;
--ALTER TABLE staff RENAME TO staffs;
ALTER TABLE staff_holiday RENAME TO staff_holidays;
ALTER TABLE staff_note RENAME TO staff_notes;
ALTER TABLE staff_summary RENAME TO staff_summaries;
ALTER TABLE supplier RENAME TO suppliers;
ALTER TABLE tag RENAME TO tags;
ALTER TABLE ticket RENAME TO tickets;
ALTER TABLE ticket_note RENAME TO ticket_notes;
ALTER TABLE time_correction RENAME TO time_corrections;
ALTER TABLE time_entry RENAME TO time_entries;

---------------------------------

CREATE TABLE public.purchase_types
(
id bigint NOT NULL,
name text COLLATE pg_catalog."default" NOT NULL,
CONSTRAINT purchase_types_pkey PRIMARY KEY (id)
);

INSERT INTO public.purchase_types VALUES (1, 'Hardware');
INSERT INTO public.purchase_types VALUES (2, 'Software');
INSERT INTO public.purchase_types VALUES (3, 'Consumption');
INSERT INTO public.purchase_types VALUES (4, 'Other');


ALTER TABLE purchases ADD COLUMN type2 bigint;
UPDATE purchases p
SET type2 = pt.id
FROM purchase_types pt
WHERE LOWER(pt.name) = LOWER(TEXT(p.type));

ALTER TABLE purchases DROP COLUMN type;
DROP TYPE purchase_type;

ALTER TABLE purchases RENAME COLUMN type2 TO type;
ALTER TABLE ONLY public.purchases ADD CONSTRAINT purchases_type_fkey FOREIGN KEY (type) REFERENCES public.purchase_types(id) ON UPDATE RESTRICT ON DELETE RESTRICT;


---------------------------------

CREATE TABLE public.tag_types
(
id bigint NOT NULL,
name text COLLATE pg_catalog."default" NOT NULL,
CONSTRAINT tag_types_pkey PRIMARY KEY (id)
);

INSERT INTO public.tag_types VALUES (1, 'Hardware');
INSERT INTO public.tag_types VALUES (2, 'Software');
INSERT INTO public.tag_types VALUES (3, 'Consumption');
INSERT INTO public.tag_types VALUES (4, 'Other');


ALTER TABLE tags ADD COLUMN type2 bigint;
UPDATE tags t
SET type2 = tt.id
FROM purchase_types tt
WHERE LOWER(tt.name) = LOWER(TEXT(t.type));

ALTER TABLE tags DROP COLUMN type;
DROP TYPE tag_type;

ALTER TABLE tags RENAME COLUMN type2 TO type;
ALTER TABLE ONLY public.tags ADD CONSTRAINT tags_type_fkey FOREIGN KEY (type) REFERENCES public.tag_types(id) ON UPDATE RESTRICT ON DELETE RESTRICT;
---------------------------------

CREATE TABLE public.ticket_priorities
(
id bigint NOT NULL,
name text COLLATE pg_catalog."default" NOT NULL,
CONSTRAINT ticket_priorities_pkey PRIMARY KEY (id)
);

INSERT INTO public.ticket_priorities VALUES (1, 'Normal');
INSERT INTO public.ticket_priorities VALUES (2, 'Urgent');
INSERT INTO public.ticket_priorities VALUES (3, 'Emergency');


ALTER TABLE tickets ADD COLUMN priority2 bigint;
UPDATE tickets t
SET priority2 = tp.id
FROM ticket_priorities tp
WHERE LOWER(tp.name) = LOWER(TEXT(t.priority));

ALTER TABLE tickets DROP COLUMN priority;
DROP TYPE ticket_priority;

ALTER TABLE tickets RENAME COLUMN priority2 TO priority;
ALTER TABLE ONLY public.tickets ADD CONSTRAINT tickets_priority_fkey FOREIGN KEY (priority) REFERENCES public.ticket_priorities(id) ON UPDATE RESTRICT ON DELETE RESTRICT;
---------------------------------

CREATE TABLE public.ticket_schedule_repetitions
(
id bigint NOT NULL,
name text COLLATE pg_catalog."default" NOT NULL,
CONSTRAINT ticket_schedule_repetitions_pkey PRIMARY KEY (id)
);

INSERT INTO public.ticket_schedule_repetitions VALUES (1, 'Daily');
INSERT INTO public.ticket_schedule_repetitions VALUES (2, 'Weekly');
INSERT INTO public.ticket_schedule_repetitions VALUES (3, 'Monthly');
INSERT INTO public.ticket_schedule_repetitions VALUES (4, 'Yearly');


ALTER TABLE tickets ADD COLUMN schedule_repetition2 bigint;
UPDATE tickets t
SET schedule_repetition2 = tp.id
FROM ticket_schedule_repetitions tp
WHERE LOWER(tp.name) = LOWER(TEXT(t.schedule_repetition));

ALTER TABLE tickets DROP COLUMN schedule_repetition;
DROP TYPE ticket_schedule_repetition;

ALTER TABLE tickets RENAME COLUMN schedule_repetition2 TO schedule_repetition;
ALTER TABLE ONLY public.tickets ADD CONSTRAINT tickets_schedule_reptition_fkey FOREIGN KEY (schedule_repetition) REFERENCES public.ticket_schedule_repetitions(id) ON UPDATE RESTRICT ON DELETE RESTRICT;
---------------------------------

CREATE TABLE public.ticket_states
(
id bigint NOT NULL,
name text COLLATE pg_catalog."default" NOT NULL,
CONSTRAINT ticket_states_pkey PRIMARY KEY (id)
);

INSERT INTO public.ticket_states VALUES (1, 'New');
INSERT INTO public.ticket_states VALUES (2, 'In_progress');
INSERT INTO public.ticket_states VALUES (3, 'Done');


ALTER TABLE tickets ADD COLUMN state bigint;
UPDATE tickets t
SET state = tp.id
FROM ticket_states tp
WHERE LOWER(tp.name) = LOWER(TEXT(t.status));

ALTER TABLE tickets DROP COLUMN status;
DROP TYPE ticket_status;

ALTER TABLE ONLY public.tickets ADD CONSTRAINT tickets_state_fkey FOREIGN KEY (state) REFERENCES public.ticket_states(id) ON UPDATE RESTRICT ON DELETE RESTRICT;

---------------------------------

CREATE TABLE public.time_entry_types
(
id bigint NOT NULL,
name text COLLATE pg_catalog."default" NOT NULL,
CONSTRAINT time_entry_types_pkey PRIMARY KEY (id)
);

INSERT INTO public.time_entry_types VALUES (1, 'Work');
INSERT INTO public.time_entry_types VALUES (2, 'Break');
INSERT INTO public.time_entry_types VALUES (3, 'Sick');
INSERT INTO public.time_entry_types VALUES (4, 'Holiday');
INSERT INTO public.time_entry_types VALUES (5, 'Holiday_unpaid');
INSERT INTO public.time_entry_types VALUES (6, 'Holiday_special');
INSERT INTO public.time_entry_types VALUES (7, 'Other_paid');
INSERT INTO public.time_entry_types VALUES (8, 'Other_unpaid');


ALTER TABLE time_entries ADD COLUMN type bigint;
UPDATE time_entries t
SET type = tp.id
FROM time_entry_types tp
WHERE LOWER(tp.name) = LOWER(TEXT(t.entry_type));

ALTER TABLE time_entries DROP COLUMN entry_type;
DROP TYPE time_entry_entry_type;

ALTER TABLE ONLY public.time_entries ADD CONSTRAINT time_entries_type_fkey FOREIGN KEY (type) REFERENCES public.time_entry_types(id) ON UPDATE RESTRICT ON DELETE RESTRICT;

CREATE SEQUENCE purchase_type_id_seq
START WITH 1
INCREMENT BY 1
NO MINVALUE
NO MAXVALUE
CACHE 1;

CREATE SEQUENCE tag_type_id_seq
START WITH 1
INCREMENT BY 1
NO MINVALUE
NO MAXVALUE
CACHE 1;

CREATE SEQUENCE ticket_priority_id_seq
START WITH 1
INCREMENT BY 1
NO MINVALUE
NO MAXVALUE
CACHE 1;

CREATE SEQUENCE ticket_schedule_repetition_id_seq
START WITH 1
INCREMENT BY 1
NO MINVALUE
NO MAXVALUE
CACHE 1;

CREATE SEQUENCE time_entry_type_id_seq
START WITH 1
INCREMENT BY 1
NO MINVALUE
NO MAXVALUE
CACHE 1;

CREATE SEQUENCE ticket_state_id_seq
START WITH 1
INCREMENT BY 1
NO MINVALUE
NO MAXVALUE
CACHE 1;

ALTER SEQUENCE purchase_type_id_seq owned by purchase_types.id;
ALTER SEQUENCE tag_type_id_seq owned by tag_types.id;
ALTER SEQUENCE ticket_priority_id_seq owned by ticket_priorities.id;
ALTER SEQUENCE ticket_schedule_repetition_id_seq owned by ticket_schedule_repetitions.id;
ALTER SEQUENCE time_entry_type_id_seq owned by time_entry_types.id;
ALTER SEQUENCE ticket_state_id_seq owned by ticket_states.id;


ALTER TABLE ONLY purchase_types ALTER COLUMN id SET DEFAULT nextval('purchase_type_id_seq'::regclass);
ALTER TABLE ONLY tag_types ALTER COLUMN id SET DEFAULT nextval('tag_type_id_seq'::regclass);
ALTER TABLE ONLY ticket_priorities ALTER COLUMN id SET DEFAULT nextval('ticket_priority_id_seq'::regclass);
ALTER TABLE ONLY ticket_schedule_repetitions ALTER COLUMN id SET DEFAULT nextval('ticket_schedule_repetition_id_seq'::regclass);
ALTER TABLE ONLY time_entry_types ALTER COLUMN id SET DEFAULT nextval('time_entry_type_id_seq'::regclass);
ALTER TABLE ONLY ticket_states ALTER COLUMN id SET DEFAULT nextval('ticket_state_id_seq'::regclass);


SELECT setval('customer_id_seq', coalesce(max(id), 0) + 1, false) FROM customers;
SELECT setval('document_id_seq', coalesce(max(id), 0) + 1, false) FROM documents;
SELECT setval('inventory_id_seq', coalesce(max(id), 0) + 1, false) FROM products;
SELECT setval('customer_site_id_seq', coalesce(max(id), 0) + 1, false) FROM customer_sites;
SELECT setval('public_holiday_id_seq', coalesce(max(id), 0) + 1, false) FROM public_holidays;
SELECT setval('purchase_customer_id_seq', coalesce(max(id), 0) + 1, false) FROM customer_purchases;
SELECT setval('purchase_id_seq', coalesce(max(id), 0) + 1, false) FROM purchases;
SELECT setval('customer_id_seq', coalesce(max(id), 0) + 1, false) FROM customers;
SELECT setval('company_id_seq', coalesce(max(id), 0) + 1, false) FROM companies;
SELECT setval('purchase_staff_id_seq', coalesce(max(id), 0) + 1, false) FROM staff_purchases;
SELECT setval('staff_holiday_id_seq', coalesce(max(id), 0) + 1, false) FROM staff_holidays;
SELECT setval('staff_id_seq', coalesce(max(id), 0) + 1, false) FROM staff;
SELECT setval('staff_note_id_seq', coalesce(max(id), 0) + 1, false) FROM customers;
SELECT setval('staff_summary_id_seq', coalesce(max(id), 0) + 1, false) FROM staff_notes;
SELECT setval('supplier_id_seq', coalesce(max(id), 0) + 1, false) FROM suppliers;
SELECT setval('tag_id_seq', coalesce(max(id), 0) + 1, false) FROM tags;
SELECT setval('ticket_id_seq', coalesce(max(id), 0) + 1, false) FROM tickets;
SELECT setval('ticket_note_id_seq', coalesce(max(id), 0) + 1, false) FROM ticket_notes;
SELECT setval('time_correction_id_seq', coalesce(max(id), 0) + 1, false) FROM time_corrections;
SELECT setval('time_entry_id_seq', coalesce(max(id), 0) + 1, false) FROM time_entries;
SELECT setval('users_id_seq', coalesce(max(id), 0) + 1, false) FROM users;
SELECT setval('purchase_type_id_seq', coalesce(max(id), 0) + 1, false) FROM purchase_types;
SELECT setval('tag_type_id_seq', coalesce(max(id), 0) + 1, false) FROM tag_types;
SELECT setval('ticket_priority_id_seq', coalesce(max(id), 0) + 1, false) FROM ticket_priorities;
SELECT setval('ticket_schedule_repetition_id_seq', coalesce(max(id), 0) + 1, false) FROM ticket_schedule_repetitions;
SELECT setval('time_entry_type_id_seq', coalesce(max(id), 0) + 1, false) FROM time_entry_types;
SELECT setval('ticket_state_id_seq', coalesce(max(id), 0) + 1, false) FROM ticket_states;


## Create Models

php artisan krlove:generate:model Company --output-path=/var/www/html/app/Model --namespace=App\\Model
php artisan krlove:generate:model Customer --output-path=/var/www/html/app/Model --namespace=App\\Model
php artisan krlove:generate:model CustomerSite --output-path=/var/www/html/app/Model --namespace=App\\Model
php artisan krlove:generate:model Document --output-path=/var/www/html/app/Model --namespace=App\\Model
php artisan krlove:generate:model Product --output-path=/var/www/html/app/Model --namespace=App\\Model
php artisan krlove:generate:model PublicHoliday --output-path=/var/www/html/app/Model --namespace=App\\Model
php artisan krlove:generate:model Purchase --output-path=/var/www/html/app/Model --namespace=App\\Model
php artisan krlove:generate:model CustomerPurchase --output-path=/var/www/html/app/Model --namespace=App\\Model
php artisan krlove:generate:model StaffPurchase --output-path=/var/www/html/app/Model --namespace=App\\Model
php artisan krlove:generate:model Staff --output-path=/var/www/html/app/Model --namespace=App\\Model
php artisan krlove:generate:model StaffHoliday --output-path=/var/www/html/app/Model --namespace=App\\Model
php artisan krlove:generate:model StaffNote --output-path=/var/www/html/app/Model --namespace=App\\Model
php artisan krlove:generate:model StaffSummary --output-path=/var/www/html/app/Model --namespace=App\\Model
php artisan krlove:generate:model Supplier --output-path=/var/www/html/app/Model --namespace=App\\Model
php artisan krlove:generate:model Tag --output-path=/var/www/html/app/Model --namespace=App\\Model
php artisan krlove:generate:model Ticket --output-path=/var/www/html/app/Model --namespace=App\\Model
php artisan krlove:generate:model TicketNote --output-path=/var/www/html/app/Model --namespace=App\\Model
php artisan krlove:generate:model TimeCorrection --output-path=/var/www/html/app/Model --namespace=App\\Model
php artisan krlove:generate:model TimeEntry --output-path=/var/www/html/app/Model --namespace=App\\Model
php artisan krlove:generate:model User --output-path=/var/www/html/app/Model --namespace=App\\Model

php artisan krlove:generate:model PurchaseType --output-path=/var/www/html/app/Model --namespace=App\\Model
php artisan krlove:generate:model TagType --output-path=/var/www/html/app/Model --namespace=App\\Model
php artisan krlove:generate:model TicketPriority --output-path=/var/www/html/app/Model --namespace=App\\Model
php artisan krlove:generate:model TicketScheduleRepetition --output-path=/var/www/html/app/Model --namespace=App\\Model
php artisan krlove:generate:model TicketState --output-path=/var/www/html/app/Model --namespace=App\\Model
php artisan krlove:generate:model TimeEntryType --output-path=/var/www/html/app/Model --namespace=App\\Model

=======================================================================================

correction strategy

if customer_purchase_id exists on product
a	then its copied to deployment using customer_purchase_id as articleIdentity

migrate products without customer_purchase_id

0

1 correct products with customer_sites but no customer

if we have purchase
assume we have a purchase

2	if we have one customerpurchase for this purchase
update product.customer_purchase_id to customerpurchase

3	if we have multiple customerpurchase for this purchase
create 5 products and link to customerpurchase
-> notify



4	if we have one staffpurchase for this purchase
update product.staff**_purchase_id to staffpurchase

5	if we have multiple staffpurchase for this purchase
create product.staff_purchase_id
create 5 products and link to staffpurchase
-> notify


assume we have not a purchase
assume we have a customer or staff
link to customer
link to staff

6	assume we have not a customer or staff
link to a staff
->notify client


=======================================================================================



ALTER TABLE public.products
ADD COLUMN purchase_staff_id bigint;

=======================================================================================

COPY (
SELECT
*
FROM public.products pr
JOIN public.purchases pu
ON pr.purchase_id = pu.id
JOIN public.customer_purchases cpu
ON cpu.purchase_id = pu.id
JOIN public.staff_purchases spu
ON spu.purchase_id = pu.id
) TO '/opt/db/01.csv' DELIMITER ',' CSV HEADER;


0 produkte, deren purchases staff und customer haben, staff wird entfernt

DELETE FROM public.staff_purchases
WHERE id IN (
SELECT spu.id
FROM public.products pr
JOIN public.purchases pu
ON pr.purchase_id = pu.id
JOIN public.customer_purchases cpu
ON cpu.purchase_id = pu.id
JOIN public.staff_purchases spu
ON spu.purchase_id = pu.id
)
;

=======================================================================================

COPY (
SELECT
*
FROM public.products pr
JOIN public.purchases pu
ON pr.purchase_id = pu.id
LEFT JOIN public.customer_purchases cpu
ON cpu.purchase_id = pu.id
LEFT JOIN public.staff_purchases spu
ON spu.purchase_id = pu.id
WHERE
pr.purchase_customer_id IS NULL
AND pr.customer_id IS NULL
-- AND pr.staff_id IS NULL
AND pr.customer_site_id IS NULL
AND cpu.id IS NULL
AND spu.id IS NULL
) TO '/opt/db/02.csv' DELIMITER ',' CSV HEADER;

purchases, die weder kunden noch staff zugeordnet sind

CREATE OR REPLACE FUNCTION pg_temp.test()
RETURNS VOID
AS $$

DECLARE
TABLE_RECORD RECORD;

BEGIN
FOR TABLE_RECORD IN
SELECT
DISTINCT(pu.id)
FROM public.products pr
JOIN public.purchases pu
ON pr.purchase_id = pu.id
WHERE
pr.id in (
SELECT
pri.id
FROM public.products pri
JOIN public.purchases pui
ON pri.purchase_id = pui.id
LEFT JOIN public.customer_purchases cpui
ON cpui.purchase_id = pui.id
LEFT JOIN public.staff_purchases spui
ON spui.purchase_id = pui.id
WHERE
pri.purchase_customer_id IS NULL
AND pri.customer_id IS NULL
-- AND pri.staff_id IS NULL
AND pri.customer_site_id IS NULL
AND cpui.id IS NULL
AND spui.id IS NULL
)

	LOOP
		WITH new_staff_purchase AS (
			INSERT INTO public.staff_purchases (purchase_id, staff_id, created_by_id, updated_by_id, created_at, updated_at, amount, amount_delivered)
			VALUES (TABLE_RECORD.id, 1, 1, 1, NOW(), NOW(), 0, 0)
			RETURNING id
		)
		UPDATE public.products pr
		SET purchase_staff_id = new_staff_purchase.id
		FROM new_staff_purchase
		WHERE pr.purchase_id = TABLE_RECORD.id
		;

	END LOOP;

END
$$ LANGUAGE plpgsql;
SELECT pg_temp.test();
DROP FUNCTION pg_temp.test();

=======================================================================================

COPY(
SELECT
*
FROM public.products pr
WHERE
pr.purchase_customer_id IS NULL
AND pr.customer_id IS NULL
--AND pr.staff_id IS NULL
AND pr.customer_site_id IS NOT NULL
) TO '/opt/db/03.csv' DELIMITER ',' CSV HEADER;

1 Dieses Produkt hat keinen customer, aber eine customer_site. Dieses Produkt wird so geändert, dass es dem Kunden zugeordnet ist.

UPDATE public.products pr
SET
customer_id = cs.customer_id
FROM public.customer_sites cs
WHERE
pr.customer_site_id = cs.id
AND pr.purchase_customer_id IS NULL
AND pr.customer_id IS NULL
--AND pr.staff_id IS NULL
AND pr.customer_site_id IS NOT NULL
;

=======================================================================================
COPY(
SELECT
pr.*
FROM public.products pr
JOIN public.purchases pu
ON pr.purchase_id = pu.id
LEFT JOIN public.customer_purchases cpu
ON cpu.purchase_id = pu.id
WHERE
pr.purchase_customer_id IS NULL
AND pr.customer_id IS NULL
-- AND pr.staff_id IS NULL
AND pr.customer_site_id IS NULL
AND cpu.customer_id IS NOT NULL
GROUP BY pr.id
HAVING
COUNT(DISTINCT cpu.*) = 1
AND COUNT(pu.*) = 1
) TO '/opt/db/04.csv' DELIMITER ',' CSV HEADER;

2 Diese Produkte haben keinen Kunden, wurden aber durch einen (!) Kunden gekauft. Diese Produkte werden dem Kunden zugeordnet.


UPDATE public.products pr
SET
purchase_customer_id = cpu.id
FROM public.purchases pu
LEFT JOIN public.customer_purchases cpu
ON cpu.purchase_id = pu.id
WHERE
pr.purchase_id = pu.id
AND pr.id IN (
SELECT
pri.id
FROM public.products pri
JOIN public.purchases pui
ON pri.purchase_id = pui.id
LEFT JOIN public.customer_purchases cpui
ON cpui.purchase_id = pui.id
WHERE
pri.purchase_customer_id IS NULL
AND pri.customer_id IS NULL
--AND pri.staff_id IS NULL
AND pri.customer_site_id IS NULL
AND cpui.customer_id IS NOT NULL
GROUP BY pri.id
HAVING
COUNT(DISTINCT cpui.*) = 1
AND COUNT(pui.*) = 1
)
;


=======================================================================================
COPY(
SELECT
pr.*
FROM public.products pr
JOIN public.purchases pu
ON pr.purchase_id = pu.id
LEFT JOIN public.customer_purchases cpu
ON cpu.purchase_id = pu.id
WHERE
pr.purchase_customer_id IS NULL
AND pr.customer_id IS NULL
--AND pr.staff_id IS NULL
AND pr.customer_site_id IS NULL
AND cpu.customer_id IS NOT NULL
GROUP BY pr.id
HAVING
COUNT(DISTINCT cpu.*) != 1
AND COUNT(pu.*) != 1
) TO '/opt/db/05.csv' DELIMITER ',' CSV HEADER;

3 Diese Produkte haben keinen Kunden, wurden aber durch mehrere Kunden gekauft. Diese werden dubliziert und sind nun mehrmals deployed!

CREATE OR REPLACE FUNCTION pg_temp.test()
RETURNS VOID
AS $$

DECLARE
TABLE_RECORD RECORD;

BEGIN
FOR TABLE_RECORD IN
SELECT
pr.*, cpu.id AS new_purchase_customer_id
FROM public.products pr
JOIN public.purchases pu
ON pr.purchase_id = pu.id
LEFT JOIN public.customer_purchases cpu
ON cpu.purchase_id = pu.id
WHERE
pr.purchase_customer_id IS NULL
AND pr.customer_id IS NULL
--AND pr.staff_id IS NULL
AND pr.customer_site_id IS NULL
AND cpu.customer_id IS NOT NULL
AND pr.id in (
SELECT pr.id
FROM public.products pr
JOIN public.purchases pu
ON pr.purchase_id = pu.id
LEFT JOIN public.customer_purchases cpu
ON cpu.purchase_id = pu.id
WHERE
pr.purchase_customer_id IS NULL
AND pr.customer_id IS NULL
--AND pr.staff_id IS NULL
AND pr.customer_site_id IS NULL
AND cpu.customer_id IS NOT NULL
GROUP BY pr.id
HAVING
COUNT(DISTINCT cpu.*) != 1
AND COUNT(pu.*) != 1
)

	LOOP
		INSERT INTO public.products(customer_id, customer_site_id, supplier_id, purchase_id, created_by_id, updated_by_id, a_number, name, type, serial_number, mac_address, license_key, billed, created_at, updated_at, staff_id, description, storage_location, billed_by_id, purchase_customer_id, billed_at)
		VALUES(
			TABLE_RECORD.customer_id, 
			TABLE_RECORD.customer_site_id, 
			TABLE_RECORD.supplier_id, 
			TABLE_RECORD.purchase_id, 
			TABLE_RECORD.created_by_id, 
			TABLE_RECORD.updated_by_id, 
			TABLE_RECORD.a_number, 
			TABLE_RECORD.name, 
			TABLE_RECORD.type, 
			TABLE_RECORD.serial_number, 
			TABLE_RECORD.mac_address, 
			TABLE_RECORD.license_key, 
			TABLE_RECORD.billed, 
			TABLE_RECORD.created_at, 
			TABLE_RECORD.updated_at, 
			TABLE_RECORD.staff_id, 
			TABLE_RECORD.description, 
			TABLE_RECORD.storage_location, 
			TABLE_RECORD.billed_by_id, 
			TABLE_RECORD.new_purchase_customer_id, 
			TABLE_RECORD.billed_at);
	END LOOP;
	
	DELETE FROM public.products
	WHERE id in (
		SELECT pr.id
		FROM public.products pr
		JOIN public.purchases pu
			ON pr.purchase_id = pu.id
		LEFT JOIN public.customer_purchases cpu
			ON cpu.purchase_id = pu.id
		WHERE 
				pr.purchase_customer_id IS NULL
			AND pr.customer_id IS NULL
			--AND pr.staff_id IS NULL
			AND pr.customer_site_id IS NULL
			AND cpu.customer_id IS NOT NULL
		GROUP BY pr.id
		HAVING
			COUNT(DISTINCT cpu.*) != 1
			AND COUNT(pu.*) != 1
			)
	;

END
$$ LANGUAGE plpgsql;
SELECT pg_temp.test();
DROP FUNCTION pg_temp.test();

=======================================================================================
purchases mit kunden (sollte leer sein):
SELECT
pr.*
FROM public.products pr
JOIN public.purchases pu
ON pr.purchase_id = pu.id
LEFT JOIN public.customer_purchases cpu
ON cpu.purchase_id = pu.id
WHERE
pr.purchase_customer_id IS NULL
AND pr.customer_id IS NULL
--AND pr.staff_id IS NULL
AND pr.customer_site_id IS NULL
AND cpu.customer_id IS NOT NULL
AND pr.purchase_id IS NOT NULL
;
=======================================================================================
COPY(
SELECT
pr.*
FROM public.products pr
JOIN public.purchases pu
ON pr.purchase_id = pu.id
JOIN public.staff_purchases spu
ON spu.purchase_id = pu.id
WHERE
pr.purchase_customer_id IS NULL
AND pr.customer_id IS NULL
--AND pr.staff_id IS NULL
AND pr.customer_site_id IS NULL
AND spu.staff_id IS NOT NULL
AND pr.purchase_staff_id IS NULL
GROUP BY pr.id
HAVING
COUNT(DISTINCT spu.*) = 1
AND COUNT(pu.*) = 1
) TO '/opt/db/06.csv' DELIMITER ',' CSV HEADER;

4 Diese Produkte haben keinen Staff, wurden aber durch einen (!) Staff gekauft. Diese Produkte werden dem Staff zugeordnet.

UPDATE public.products pr
SET
purchase_staff_id = spu.id
FROM public.purchases pu
LEFT JOIN public.staff_purchases spu
ON spu.purchase_id = pu.id
WHERE
pr.purchase_id = pu.id
AND pr.id IN (
SELECT
pri.id
FROM public.products pri
JOIN public.purchases pui
ON pri.purchase_id = pui.id
JOIN public.staff_purchases spui
ON spui.purchase_id = pui.id
WHERE
pri.purchase_customer_id IS NULL
AND pri.customer_id IS NULL
--AND pri.staff_id IS NULL
AND pri.customer_site_id IS NULL
AND spui.staff_id IS NOT NULL
AND pri.purchase_staff_id IS NULL
GROUP BY pri.id
HAVING
COUNT(DISTINCT spui.*) = 1
AND COUNT(pui.*) = 1
)
;


=======================================================================================
COPY(
SELECT
pr.*
FROM public.products pr
JOIN public.purchases pu
ON pr.purchase_id = pu.id
JOIN public.staff_purchases spu
ON spu.purchase_id = pu.id
WHERE
pr.purchase_customer_id IS NULL
AND pr.customer_id IS NULL
--AND pr.staff_id IS NULL
AND pr.customer_site_id IS NULL
AND spu.staff_id IS NOT NULL
AND pr.purchase_staff_id IS NULL
GROUP BY pr.id
HAVING
COUNT(DISTINCT spu.*) != 1
AND COUNT(pu.*) != 1
) TO '/opt/db/07.csv' DELIMITER ',' CSV HEADER;

5 Diese Produkte haben keinen Staff, wurden aber durch mehrere Staffs gekauft. Diese werden dubliziert und sind nun mehrmals deployed!

CREATE OR REPLACE FUNCTION pg_temp.test()
RETURNS VOID
AS $$

DECLARE
TABLE_RECORD RECORD;

BEGIN
FOR TABLE_RECORD IN
SELECT
pr.*, spu.id AS new_purchase_staff_id
FROM public.products pr
JOIN public.purchases pu
ON pr.purchase_id = pu.id
JOIN public.staff_purchases spu
ON spu.purchase_id = pu.id
WHERE
pr.purchase_customer_id IS NULL
AND pr.customer_id IS NULL
--AND pr.staff_id IS NULL
AND pr.customer_site_id IS NULL
AND spu.staff_id IS NOT NULL
AND pr.purchase_staff_id IS NULL
AND pr.id in (
SELECT pr.id
FROM public.products pr
JOIN public.purchases pu
ON pr.purchase_id = pu.id
JOIN public.staff_purchases spu
ON spu.purchase_id = pu.id
WHERE
pr.purchase_customer_id IS NULL
AND pr.customer_id IS NULL
--AND pr.staff_id IS NULL
AND pr.customer_site_id IS NULL
AND spu.staff_id IS NOT NULL
AND pr.purchase_staff_id IS NULL
GROUP BY pr.id
HAVING
COUNT(DISTINCT spu.*) != 1
AND COUNT(pu.*) != 1
)

	LOOP
		INSERT INTO public.products(customer_id, customer_site_id, supplier_id, purchase_id, created_by_id, updated_by_id, a_number, name, type, serial_number, mac_address, license_key, billed, created_at, updated_at, staff_id, description, storage_location, billed_by_id, purchase_customer_id, billed_at, purchase_staff_id)
		VALUES(
			TABLE_RECORD.customer_id, 
			TABLE_RECORD.customer_site_id, 
			TABLE_RECORD.supplier_id, 
			TABLE_RECORD.purchase_id, 
			TABLE_RECORD.created_by_id, 
			TABLE_RECORD.updated_by_id, 
			TABLE_RECORD.a_number, 
			TABLE_RECORD.name, 
			TABLE_RECORD.type, 
			TABLE_RECORD.serial_number, 
			TABLE_RECORD.mac_address, 
			TABLE_RECORD.license_key, 
			TABLE_RECORD.billed, 
			TABLE_RECORD.created_at, 
			TABLE_RECORD.updated_at, 
			TABLE_RECORD.staff_id, 
			TABLE_RECORD.description, 
			TABLE_RECORD.storage_location, 
			TABLE_RECORD.billed_by_id, 
			null, 
			TABLE_RECORD.billed_at,
			TABLE_RECORD.new_purchase_staff_id);
	END LOOP;
	
	DELETE FROM public.products
	WHERE id in (
		SELECT pr.id
		FROM public.products pr
		JOIN public.purchases pu
			ON pr.purchase_id = pu.id
		JOIN public.staff_purchases spu
			ON spu.purchase_id = pu.id
		WHERE 
				pr.purchase_customer_id IS NULL
			AND pr.customer_id IS NULL
			--AND pr.staff_id IS NULL
			AND pr.customer_site_id IS NULL
			AND spu.staff_id IS NOT NULL
			AND pr.purchase_staff_id IS NULL
		GROUP BY pr.id
		HAVING
			COUNT(DISTINCT spu.*) != 1
			AND COUNT(pu.*) != 1
			)
	;

END
$$ LANGUAGE plpgsql;
SELECT pg_temp.test();
DROP FUNCTION pg_temp.test();
=======================================================================================
COPY(
SELECT
pr.*
FROM public.products pr
WHERE
pr.purchase_customer_id IS NULL
AND pr.customer_id IS NULL
AND pr.staff_id IS NULL
AND pr.customer_site_id IS NULL
AND pr.purchase_id IS NULL
) TO '/opt/db/08.csv' DELIMITER ',' CSV HEADER;

6 produkte ohne staff und customer, werden staff 1 zugeordnet

UPDATE public.products
SET
staff_id = 1
WHERE
purchase_customer_id IS NULL
AND customer_id IS NULL
AND staff_id IS NULL
AND customer_site_id IS NULL
AND purchase_id IS NULL
;

=======================================================================================
sollte leer sein

SELECT
*
FROM public.products pr
LEFT JOIN public.purchases pu
ON pr.purchase_id = pu.id
LEFT JOIN public.customer_purchases cpu
ON cpu.purchase_id = pu.id
LEFT JOIN public.staff_purchases spu
ON spu.purchase_id = pu.id
WHERE
pr.purchase_customer_id IS NULL
AND pr.customer_id IS NULL
AND pr.staff_id IS NULL
AND pr.purchase_staff_id IS NULL
;

ALTER TABLE public.products DROP COLUMN purchase_id;

SELECT
*
FROM public.products pr
LEFT JOIN public.customer_purchases cpu
ON pr.purchase_customer_id = cpu.id
LEFT JOIN public.staff_purchases spu
ON pr.purchase_staff_id = spu.id
WHERE
pr.customer_id IS NULL
AND pr.staff_id IS NULL
AND pr.purchase_customer_id IS NULL
AND pr.purchase_staff_id IS NULL
;
