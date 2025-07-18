--
-- PostgreSQL database dump
--

-- Dumped from database version 16.9 (Ubuntu 16.9-0ubuntu0.24.04.1)
-- Dumped by pg_dump version 16.9 (Ubuntu 16.9-0ubuntu0.24.04.1)

-- Started on 2025-07-18 11:26:11 GMT

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- TOC entry 218 (class 1259 OID 17273)
-- Name: compte; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.compte (
    id integer NOT NULL,
    numero character varying(20) NOT NULL,
    datecreation timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    solde numeric(15,2) DEFAULT 0.00,
    numerotel character varying(20) NOT NULL,
    typecompte character varying(20) NOT NULL,
    userid integer NOT NULL,
    CONSTRAINT compte_typecompte_check CHECK (((typecompte)::text = ANY ((ARRAY['principal'::character varying, 'secondaire'::character varying])::text[])))
);


ALTER TABLE public.compte OWNER TO postgres;

--
-- TOC entry 217 (class 1259 OID 17272)
-- Name: compte_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.compte_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.compte_id_seq OWNER TO postgres;

--
-- TOC entry 3444 (class 0 OID 0)
-- Dependencies: 217
-- Name: compte_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.compte_id_seq OWNED BY public.compte.id;


--
-- TOC entry 220 (class 1259 OID 17290)
-- Name: transaction; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.transaction (
    id integer NOT NULL,
    date timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    typetransaction character varying(20) NOT NULL,
    montant numeric(15,2) NOT NULL,
    compteid integer NOT NULL,
    CONSTRAINT transaction_typetransaction_check CHECK (((typetransaction)::text = ANY ((ARRAY['depot'::character varying, 'retrait'::character varying, 'paiement'::character varying])::text[])))
);


ALTER TABLE public.transaction OWNER TO postgres;

--
-- TOC entry 219 (class 1259 OID 17289)
-- Name: transaction_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.transaction_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.transaction_id_seq OWNER TO postgres;

--
-- TOC entry 3445 (class 0 OID 0)
-- Dependencies: 219
-- Name: transaction_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.transaction_id_seq OWNED BY public.transaction.id;


--
-- TOC entry 216 (class 1259 OID 17241)
-- Name: users; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.users (
    id integer NOT NULL,
    nom character varying(100) NOT NULL,
    prenom character varying(100) NOT NULL,
    login character varying(50),
    password character varying(255) NOT NULL,
    numerocarteidentite character varying(50),
    photorecto character varying(255),
    photoverso character varying(255),
    adresse character varying(255),
    typeuser character varying(20) NOT NULL,
    CONSTRAINT users_typeuser_check CHECK (((typeuser)::text = ANY ((ARRAY['client'::character varying, 'service_commercial'::character varying])::text[])))
);


ALTER TABLE public.users OWNER TO postgres;

--
-- TOC entry 215 (class 1259 OID 17240)
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.users_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.users_id_seq OWNER TO postgres;

--
-- TOC entry 3446 (class 0 OID 0)
-- Dependencies: 215
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.users_id_seq OWNED BY public.users.id;


--
-- TOC entry 3268 (class 2604 OID 17276)
-- Name: compte id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.compte ALTER COLUMN id SET DEFAULT nextval('public.compte_id_seq'::regclass);


--
-- TOC entry 3271 (class 2604 OID 17293)
-- Name: transaction id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.transaction ALTER COLUMN id SET DEFAULT nextval('public.transaction_id_seq'::regclass);


--
-- TOC entry 3267 (class 2604 OID 17244)
-- Name: users id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users ALTER COLUMN id SET DEFAULT nextval('public.users_id_seq'::regclass);


--
-- TOC entry 3436 (class 0 OID 17273)
-- Dependencies: 218
-- Data for Name: compte; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.compte (id, numero, datecreation, solde, numerotel, typecompte, userid) FROM stdin;
36	CPT-687a26cdd829f	2025-07-18 10:49:49	30000.00	775159909	secondaire	2
37	CPT-687a2db6c7652	2025-07-18 11:19:18	5000.00	773452800	secondaire	2
2	2	2024-12-12 00:00:00	465000.00	778801947	principal	2
\.


--
-- TOC entry 3438 (class 0 OID 17290)
-- Dependencies: 220
-- Data for Name: transaction; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.transaction (id, date, typetransaction, montant, compteid) FROM stdin;
2	2024-12-12 00:00:00	depot	10000.00	2
1	2025-07-12 00:00:00	retrait	12000.00	2
11	2025-07-12 00:00:00	retrait	50888.00	2
3	2025-07-12 00:00:00	depot	500.00	2
4	2025-07-12 00:00:00	paiement	6000.00	2
5	2025-07-14 00:00:00	depot	1000.00	2
6	2025-07-20 00:00:00	retrait	7000.00	2
7	2025-06-12 00:00:00	depot	7900.00	2
8	2025-07-12 00:00:00	depot	22222.00	2
9	2025-07-01 00:00:00	paiement	322.00	2
10	2025-07-12 00:00:00	depot	7770.00	2
12	2002-06-20 00:00:00	depot	37890.00	2
13	2000-03-12 00:00:00	paiement	12345.00	2
15	2024-09-12 00:00:00	paiement	40000.00	2
\.


--
-- TOC entry 3434 (class 0 OID 17241)
-- Dependencies: 216
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.users (id, nom, prenom, login, password, numerocarteidentite, photorecto, photoverso, adresse, typeuser) FROM stdin;
2	Niang	Madié	die	passer123	21234567890	\N	\N	\N	client
1	Niang	aidasa	aida	passer	21388888880	\N	\N	\N	client
\.


--
-- TOC entry 3447 (class 0 OID 0)
-- Dependencies: 217
-- Name: compte_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.compte_id_seq', 37, true);


--
-- TOC entry 3448 (class 0 OID 0)
-- Dependencies: 219
-- Name: transaction_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.transaction_id_seq', 1, true);


--
-- TOC entry 3449 (class 0 OID 0)
-- Dependencies: 215
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.users_id_seq', 2, true);


--
-- TOC entry 3283 (class 2606 OID 17283)
-- Name: compte compte_numero_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.compte
    ADD CONSTRAINT compte_numero_key UNIQUE (numero);


--
-- TOC entry 3285 (class 2606 OID 17281)
-- Name: compte compte_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.compte
    ADD CONSTRAINT compte_pkey PRIMARY KEY (id);


--
-- TOC entry 3287 (class 2606 OID 17297)
-- Name: transaction transaction_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.transaction
    ADD CONSTRAINT transaction_pkey PRIMARY KEY (id);


--
-- TOC entry 3277 (class 2606 OID 17251)
-- Name: users users_login_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_login_key UNIQUE (login);


--
-- TOC entry 3279 (class 2606 OID 17253)
-- Name: users users_numerocarteidentite_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_numerocarteidentite_key UNIQUE (numerocarteidentite);


--
-- TOC entry 3281 (class 2606 OID 17249)
-- Name: users users_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- TOC entry 3288 (class 2606 OID 17284)
-- Name: compte compte_userid_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.compte
    ADD CONSTRAINT compte_userid_fkey FOREIGN KEY (userid) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- TOC entry 3289 (class 2606 OID 17298)
-- Name: transaction transaction_compteid_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.transaction
    ADD CONSTRAINT transaction_compteid_fkey FOREIGN KEY (compteid) REFERENCES public.compte(id) ON DELETE CASCADE;


-- Completed on 2025-07-18 11:26:11 GMT

--
-- PostgreSQL database dump complete
--
