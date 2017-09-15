CREATE SCHEMA IF NOT EXISTS "extensions";
CREATE EXTENSION IF NOT EXISTS hstore SCHEMA "extensions";
CREATE TYPE "gender" AS ENUM ('male', 'female');

-- ----------------------------
-- Table structure for customer
-- ----------------------------

DROP TABLE IF EXISTS "customer";
CREATE TABLE "customer" (
  "id" uuid NOT NULL,
  "brainysoft_id" varchar(255) COLLATE "default" DEFAULT NULL::character varying,
  "email" varchar(255) COLLATE "default" NOT NULL,
  "phone" varchar(255) COLLATE "default" NOT NULL,
  "password" varchar(255) COLLATE "default" NOT NULL,
  "is_phone_verified" bool DEFAULT false,
  "is_email_verified" bool DEFAULT false,
  "two_factor_auth" bool DEFAULT true,
  "first_name" varchar(255) COLLATE "default" DEFAULT NULL::character varying,
  "middle_name" varchar(255) COLLATE "default" DEFAULT NULL::character varying,
  "last_name" varchar(255) COLLATE "default" DEFAULT NULL::character varying,
  "date_of_birth" date,
  "place_of_birth" varchar(255) COLLATE "default" DEFAULT NULL::character varying,
  "gender" "gender",
  "documents" "extensions"."hstore",
  "registration_address" "extensions"."hstore",
  "actual_address" "extensions"."hstore",
  "monthly_income" numeric(18,2) DEFAULT NULL::numeric,
  "monthly_consumption" numeric(18,2) DEFAULT NULL::numeric,
  "monthly_payment" numeric(18,2) DEFAULT NULL::numeric,
  "dependant" int4,
  "active_loans" int4,
  "created_at" timestamptz(6),
  "updated_at" timestamptz(6),
  "last_ip" inet
);

-- ----------------------------
-- Indexes structure for table customer
-- ----------------------------
CREATE UNIQUE INDEX "customer_email_idx" ON "customer" USING btree ("email");
CREATE UNIQUE INDEX "customer_phone_idx" ON "customer" USING btree ("phone");

-- ----------------------------
-- Primary Key structure for table customer
-- ----------------------------
ALTER TABLE "customer" ADD PRIMARY KEY ("id");