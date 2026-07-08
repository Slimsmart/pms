-- MySQL dump 10.13  Distrib 8.0.46, for Linux (x86_64)
--
-- Host: localhost    Database: pms
-- ------------------------------------------------------
-- Server version	8.0.46-0ubuntu0.22.04.3

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admin` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin`
--

LOCK TABLES `admin` WRITE;
/*!40000 ALTER TABLE `admin` DISABLE KEYS */;
INSERT INTO `admin` VALUES (1,'admin','12345');
/*!40000 ALTER TABLE `admin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `allocation`
--

DROP TABLE IF EXISTS `allocation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `allocation` (
  `id` int NOT NULL AUTO_INCREMENT,
  `student` varchar(200) NOT NULL,
  `staff` varchar(200) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `student` (`student`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `allocation`
--

LOCK TABLES `allocation` WRITE;
/*!40000 ALTER TABLE `allocation` DISABLE KEYS */;
INSERT INTO `allocation` VALUES (4,'stu001','ST001'),(5,'abc1234567890','ST001');
/*!40000 ALTER TABLE `allocation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `chapters`
--

DROP TABLE IF EXISTS `chapters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `chapters` (
  `id` int NOT NULL AUTO_INCREMENT,
  `project_id` int NOT NULL,
  `username` varchar(20) NOT NULL,
  `chapter` enum('1','2','3','4','5','6') NOT NULL,
  `content` text NOT NULL,
  `approved` enum('-1','0','1','') NOT NULL DEFAULT '0',
  `link` varchar(100) NOT NULL,
  `comment` text,
  `plagiarism_score` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `chapters`
--

LOCK TABLES `chapters` WRITE;
/*!40000 ALTER TABLE `chapters` DISABLE KEYS */;
INSERT INTO `chapters` VALUES (1,3,'stu001','1','\nBUSINESS PLANGuyana Global Connect (GGC)The Digital Gateway to Guyana’s Business EcosystemExecutive SummaryGuyana Global Connect (GGC) is a structured digital business directory and lead-generation platform designed to connect:Guyanese businessesLocal customersInternational investorsContractors and suppliersGuyana is one of the fastest-growing economies globally, driven by oil &amp; gas expansion, infrastructure growth, and foreign investment. However, the country lacks a centralized, optimized, and trusted digital business discovery platform.Many businesses:Do not have websitesHave poor online visibilityAre difficult to verifyMiss out on international opportunitiesGGC solves this by becoming the national digital business hub, offering structured discovery, visibility, trust verification, and lead generation.Revenue will be generated through:Subscription plansVerification servicesFeatured listingsSponsored advertisingTender board access (Phase II/III)The model is scalable, achievable, and aligned with Guyana’s economic growth.2.Market OpportunityA. Local Market GapNo centralized, structured national business directoryLimited online presence among SMEsScattered and outdated business informationTrust concerns between buyers and suppliersB. International OpportunityForeign investors seek verified local suppliersOil &amp; gas contractors need reliable service directoriesProcurement teams require structured vendor discoveryGuyana’s economic expansion creates a strong need for a trusted digital business ecosystem.3.The SolutionGuyana Global Connect provides:Structured, searchable business listingsClaimable business profilesCategorized supplier discoveryLead generation toolsTrust verification systemSponsored visibility optionsGGC aims to become the “Google of Guyanese Businesses” optimized for search, trust, and commercial visibility.4.Target MarketPrimary Paying CustomersSMEs (construction, logistics, retail, hospitality)Oil &amp; gas service providersExportersProfessional services (law firms, accountants)Large contractors and suppliersSecondary Users (Traffic Drivers)Local consumersInternational investorsProcurement managersGovernment agenciesJob seekers5.Revenue Model (Achievable &amp; Phased)Revenue generation will be gradual and structured in phases.5.1 Subscription Revenue (Core Revenue)Free PlanBasic listingClaim optionLimited visibilityNo analyticsPurpose: Drive adoption and database growth.Standard Plan ($25/month)LogoPhotos (up to 5)Social linksWhatsApp contact buttonBasic visibility boostTarget: SMEsPremium Plan ($75/month)Priority category placementLead inquiry formAnalytics dashboardHomepage feature rotationVerification eligibilityTarget: Growing businesses &amp; contractorsEnterprise Plan ($250/month)Category sponsorshipBanner placementTender board access (Phase II)Data insights (basic reports)Dedicated supportTarget: Oil &amp; gas suppliers, large firms5.2 Trust Verification RevenueTrust will be monetized separately to maintain credibility.Basic Verification – $75/yearBusiness registration checkContact verificationVerification badgeFull Verification – $150/yearDocumentation reviewAddress confirmationEnhanced badgeThis builds confidence for investors and procurement teams.5.3 Sponsored AdvertisingRevenue through:Homepage bannersCategory featured spotsNewsletter sponsorshipSponsored blog contentFlat monthly pricing between $100–$500 depending on placement.5.4 Tender &amp; Opportunity Board (Phase II)Businesses can:Access private tendersReceive tender alertsPost RFQsSubscription-based access (Premium &amp; Enterprise tiers).6.Realistic Revenue Projection (Conservative)Year 1 (Conservative Target – 12 Months)60 Standard Plans × $25 = $1,500/month25 Premium Plans × $75 = $1,875/month5 Enterprise Plans × $250 = $1,250/month20 Verifications/year = ~$125/month equivalentAds &amp; Featured Spots = $750/monthEstimated Monthly Revenue (End of Year 1): $5,500/monthEstimated Annual Revenue: $66,000This is realistic for a focused national launch.Growth accelerates with traffic and authority.7.Growth StrategyPhase 1 – Data &amp; Launch (Months 1–3)Populate 1,000–3,000 businessesLaunch claim workflowImplement SEO structureActivate social channelsGoal: Visibility + database depth.Phase 2 – Traffic Growth (Months 4–6)SEO blog articlesIndustry-specific pagesLinkedIn outreachChamber partnershipsGoal: Increase search traffic and authority.Phase 3 – Monetization Push (Months 7–9)Direct sales outreachLimited-time upgrade campaignsVerification awareness campaignEmail marketingGoal: Convert free listings to paid.Phase 4 – Authority Expansion (Months 10–12)Business awards &amp; rankingsSupplier spotlight featuresOil &amp; gas supplier category pushGovernment or association partnershipsGoal: Become nationally recognized platform.8.Competitive AdvantageGGC differentiates itself through:Structured categorizationLead tracking systemTrust verification badgesAnalytics dashboardOil &amp; gas supplier targetingSEO-optimized national presenceNo single platform in Guyana currently combines all these elements.9.Key Success FactorsAccurate and structured business dataStrong SEO implementationClear monetization logicAggressive business outreachTrust credibility and verification integrity10.Risk &amp; MitigationRisk: Low TrafficMitigation:Strong SEOContent marketingStrategic partnershipsRisk: Businesses Resist PaymentMitigation:Free value firstDemonstrate analyticsIntroductory pricingRisk: Data Accuracy ComplaintsMitigation:Clear disclaimerClaim workflowEasy update process11. Long-Term Vision (3–5 Years)GGC evolves into:Guyana’s official digital business directoryTrusted supplier discovery platformB2B marketplaceTender opportunity hubBusiness data intelligence providerUltimately, it becomes part of Guyana’s commercial digital infrastructure.12. Clear Revenue Logic SummaryThe platform generates revenue because:Businesses pay for visibilityBusinesses pay for lead accessBusinesses pay for verification trustBusinesses pay for premium placementBusinesses pay for tender accessTraffic + Trust + Leads = Sustainable Revenue.\n','1','stu202602122853GGC BUSINESS PLAN.docx',NULL,NULL),(2,1,'abc1234567890','1','\nBUSINESS PLANGuyana Global Connect (GGC)The Digital Gateway to Guyana’s Business EcosystemExecutive SummaryGuyana Global Connect (GGC) is a structured digital business directory and lead-generation platform designed to connect:Guyanese businessesLocal customersInternational investorsContractors and suppliersGuyana is one of the fastest-growing economies globally, driven by oil &amp; gas expansion, infrastructure growth, and foreign investment. However, the country lacks a centralized, optimized, and trusted digital business discovery platform.Many businesses:Do not have websitesHave poor online visibilityAre difficult to verifyMiss out on international opportunitiesGGC solves this by becoming the national digital business hub, offering structured discovery, visibility, trust verification, and lead generation.Revenue will be generated through:Subscription plansVerification servicesFeatured listingsSponsored advertisingTender board access (Phase II/III)The model is scalable, achievable, and aligned with Guyana’s economic growth.2.Market OpportunityA. Local Market GapNo centralized, structured national business directoryLimited online presence among SMEsScattered and outdated business informationTrust concerns between buyers and suppliersB. International OpportunityForeign investors seek verified local suppliersOil &amp; gas contractors need reliable service directoriesProcurement teams require structured vendor discoveryGuyana’s economic expansion creates a strong need for a trusted digital business ecosystem.3.The SolutionGuyana Global Connect provides:Structured, searchable business listingsClaimable business profilesCategorized supplier discoveryLead generation toolsTrust verification systemSponsored visibility optionsGGC aims to become the “Google of Guyanese Businesses” optimized for search, trust, and commercial visibility.4.Target MarketPrimary Paying CustomersSMEs (construction, logistics, retail, hospitality)Oil &amp; gas service providersExportersProfessional services (law firms, accountants)Large contractors and suppliersSecondary Users (Traffic Drivers)Local consumersInternational investorsProcurement managersGovernment agenciesJob seekers5.Revenue Model (Achievable &amp; Phased)Revenue generation will be gradual and structured in phases.5.1 Subscription Revenue (Core Revenue)Free PlanBasic listingClaim optionLimited visibilityNo analyticsPurpose: Drive adoption and database growth.Standard Plan ($25/month)LogoPhotos (up to 5)Social linksWhatsApp contact buttonBasic visibility boostTarget: SMEsPremium Plan ($75/month)Priority category placementLead inquiry formAnalytics dashboardHomepage feature rotationVerification eligibilityTarget: Growing businesses &amp; contractorsEnterprise Plan ($250/month)Category sponsorshipBanner placementTender board access (Phase II)Data insights (basic reports)Dedicated supportTarget: Oil &amp; gas suppliers, large firms5.2 Trust Verification RevenueTrust will be monetized separately to maintain credibility.Basic Verification – $75/yearBusiness registration checkContact verificationVerification badgeFull Verification – $150/yearDocumentation reviewAddress confirmationEnhanced badgeThis builds confidence for investors and procurement teams.5.3 Sponsored AdvertisingRevenue through:Homepage bannersCategory featured spotsNewsletter sponsorshipSponsored blog contentFlat monthly pricing between $100–$500 depending on placement.5.4 Tender &amp; Opportunity Board (Phase II)Businesses can:Access private tendersReceive tender alertsPost RFQsSubscription-based access (Premium &amp; Enterprise tiers).6.Realistic Revenue Projection (Conservative)Year 1 (Conservative Target – 12 Months)60 Standard Plans × $25 = $1,500/month25 Premium Plans × $75 = $1,875/month5 Enterprise Plans × $250 = $1,250/month20 Verifications/year = ~$125/month equivalentAds &amp; Featured Spots = $750/monthEstimated Monthly Revenue (End of Year 1): $5,500/monthEstimated Annual Revenue: $66,000This is realistic for a focused national launch.Growth accelerates with traffic and authority.7.Growth StrategyPhase 1 – Data &amp; Launch (Months 1–3)Populate 1,000–3,000 businessesLaunch claim workflowImplement SEO structureActivate social channelsGoal: Visibility + database depth.Phase 2 – Traffic Growth (Months 4–6)SEO blog articlesIndustry-specific pagesLinkedIn outreachChamber partnershipsGoal: Increase search traffic and authority.Phase 3 – Monetization Push (Months 7–9)Direct sales outreachLimited-time upgrade campaignsVerification awareness campaignEmail marketingGoal: Convert free listings to paid.Phase 4 – Authority Expansion (Months 10–12)Business awards &amp; rankingsSupplier spotlight featuresOil &amp; gas supplier category pushGovernment or association partnershipsGoal: Become nationally recognized platform.8.Competitive AdvantageGGC differentiates itself through:Structured categorizationLead tracking systemTrust verification badgesAnalytics dashboardOil &amp; gas supplier targetingSEO-optimized national presenceNo single platform in Guyana currently combines all these elements.9.Key Success FactorsAccurate and structured business dataStrong SEO implementationClear monetization logicAggressive business outreachTrust credibility and verification integrity10.Risk &amp; MitigationRisk: Low TrafficMitigation:Strong SEOContent marketingStrategic partnershipsRisk: Businesses Resist PaymentMitigation:Free value firstDemonstrate analyticsIntroductory pricingRisk: Data Accuracy ComplaintsMitigation:Clear disclaimerClaim workflowEasy update process11. Long-Term Vision (3–5 Years)GGC evolves into:Guyana’s official digital business directoryTrusted supplier discovery platformB2B marketplaceTender opportunity hubBusiness data intelligence providerUltimately, it becomes part of Guyana’s commercial digital infrastructure.12. Clear Revenue Logic SummaryThe platform generates revenue because:Businesses pay for visibilityBusinesses pay for lead accessBusinesses pay for verification trustBusinesses pay for premium placementBusinesses pay for tender accessTraffic + Trust + Leads = Sustainable Revenue.\n','-1','abc202602123056GGC BUSINESS PLAN.docx',NULL,NULL),(3,4,'testuser','1','Artificial intelligence is intelligence demonstrated by machines, as opposed to the natural intelligence displayed by animals including humans. AI research has been defined as the field of study of intelligent agents, which refers to any system that perceives its environment and takes actions that maximize its chance of achieving its goals.','0','test.pdf',NULL,0);
/*!40000 ALTER TABLE `chapters` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `projects`
--

DROP TABLE IF EXISTS `projects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `projects` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `topic` text NOT NULL,
  `abstract` varchar(100) NOT NULL,
  `abstract_content` longtext NOT NULL,
  `hits` int NOT NULL DEFAULT '0',
  `approved` enum('1','0','-1') NOT NULL DEFAULT '0',
  `comments` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `projects`
--

LOCK TABLES `projects` WRITE;
/*!40000 ALTER TABLE `projects` DISABLE KEYS */;
INSERT INTO `projects` VALUES (1,'abc1234567890','Testing','doc202602120100todo\'s.docx','\nData infrastructure and structured deployment of collected business listingsBusiness claim workflow system with verification and admin approvalTrust verification system for businessesPayment integration and subscription managementLead generation and inquiry formsAnalytics dashboard for business performanceSEO optimization and website performance improvementsReview and rating systemProduction deployment and demo-ready preparationMilestoneDeliverables1Upon completion of Business Claim Workflow &amp; Data Deployment2Payment integration + subscription system update + lead system + trust verification3Final deployment + SEO + analytics dashboard + demo-ready handover\n',1,'1',NULL),(2,'stu001','Hello World','doc202602120425hello.docx','\nData infrastructure and structured deployment of collected business listingsBusiness claim workflow system with verification and admin approvalTrust verification system for businessesPayment integration and subscription managementLead generation and inquiry formsAnalytics dashboard for business performanceSEO optimization and website performance improvementsReview and rating systemProduction deployment and demo-ready preparationMilestoneDeliverables1Upon completion of Business Claim Workflow &amp; Data Deployment2Payment integration + subscription system update + lead system + trust verification3Final deployment + SEO + analytics dashboard + demo-ready handover\n',0,'-1',NULL),(3,'stu001','Welcome','doc202602121828hello.docx','\nPayment integration and subscription managementLead generation and inquiry formsAnalytics dashboard for business performanceSEO optimization and website performance improvementsReview and rating systemProduction deployment and demo-ready preparationMilestoneDeliverables1Upon completion of Business Claim Workflow &amp; Data Deployment2Payment integration + subscription system update + lead system + trust verification3Final deployment + SEO + analytics dashboard + demo-ready handover\n',0,'1',NULL),(4,'testuser','Test Project','Test','Test',0,'1',NULL);
/*!40000 ALTER TABLE `projects` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `staff_preferences`
--

DROP TABLE IF EXISTS `staff_preferences`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `staff_preferences` (
  `id` int NOT NULL AUTO_INCREMENT,
  `staff_username` varchar(100) NOT NULL,
  `student_username` varchar(100) NOT NULL,
  `preference_rank` int NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_staff_student` (`staff_username`,`student_username`),
  KEY `student_username` (`student_username`),
  CONSTRAINT `staff_preferences_ibfk_1` FOREIGN KEY (`staff_username`) REFERENCES `users` (`username`) ON DELETE CASCADE,
  CONSTRAINT `staff_preferences_ibfk_2` FOREIGN KEY (`student_username`) REFERENCES `users` (`username`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `staff_preferences`
--

LOCK TABLES `staff_preferences` WRITE;
/*!40000 ALTER TABLE `staff_preferences` DISABLE KEYS */;
/*!40000 ALTER TABLE `staff_preferences` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `student_preferences`
--

DROP TABLE IF EXISTS `student_preferences`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `student_preferences` (
  `id` int NOT NULL AUTO_INCREMENT,
  `student_username` varchar(100) NOT NULL,
  `staff_username` varchar(100) NOT NULL,
  `preference_rank` int NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_student_staff` (`student_username`,`staff_username`),
  KEY `staff_username` (`staff_username`),
  CONSTRAINT `student_preferences_ibfk_1` FOREIGN KEY (`student_username`) REFERENCES `users` (`username`) ON DELETE CASCADE,
  CONSTRAINT `student_preferences_ibfk_2` FOREIGN KEY (`staff_username`) REFERENCES `users` (`username`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `student_preferences`
--

LOCK TABLES `student_preferences` WRITE;
/*!40000 ALTER TABLE `student_preferences` DISABLE KEYS */;
INSERT INTO `student_preferences` VALUES (2,'stu001','ST001',1);
/*!40000 ALTER TABLE `student_preferences` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `uploads`
--

DROP TABLE IF EXISTS `uploads`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `uploads` (
  `id` int NOT NULL AUTO_INCREMENT,
  `file_name` varchar(100) NOT NULL,
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `uploader` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `uploads`
--

LOCK TABLES `uploads` WRITE;
/*!40000 ALTER TABLE `uploads` DISABLE KEYS */;
/*!40000 ALTER TABLE `uploads` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(200) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `staff` enum('0','1') NOT NULL DEFAULT '0',
  `session` varchar(20) NOT NULL DEFAULT 'NIL',
  `capacity` int DEFAULT '5',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'ST001','827ccb0eea8a706c4c34a16891f84e7b','Samuel Dugga','08100865905','1','NIL',5),(3,'stu001','827ccb0eea8a706c4c34a16891f84e7b','Iloka Francis Onyeka','09074617396','0','2025/2026',5),(4,'abc1234567890','827ccb0eea8a706c4c34a16891f84e7b','John Doe','09074617396','0','2025/2026',5),(5,'testuser','827ccb0eea8a706c4c34a16891f84e7b','Test Student','','0','NIL',5);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-07-08 11:12:04
