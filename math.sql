-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 10, 2020 at 09:16 PM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.3.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `math`
--

-- --------------------------------------------------------

--
-- Table structure for table `answer`
--

CREATE TABLE `answer` (
  `id` int(11) NOT NULL,
  `exam_id` int(11) NOT NULL,
  `mark` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `date` date DEFAULT NULL,
  `lesson_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(255) NOT NULL,
  `category_description` text NOT NULL,
  `ordering` int(11) DEFAULT 0,
  `parent` int(11) NOT NULL,
  `Visibility` tinyint(4) NOT NULL,
  `Allow_Comment` tinyint(4) NOT NULL,
  `Allow_Ads` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`category_id`, `category_name`, `category_description`, `ordering`, `parent`, `Visibility`, `Allow_Comment`, `Allow_Ads`) VALUES
(6, 'الصف الاول الاعدادى', 'محتوي منهج لصف الاول الاعدادى', 1, 0, 0, 0, 0),
(7, 'الصف الثاني الاعدادى', 'محتوى منهج الصف الثانى الاعدادى', 2, 0, 0, 0, 0),
(8, 'الصف الثالث الاعدادى', 'محتوى منهج الصف الثالث الاعدادى', 3, 0, 0, 0, 0),
(9, 'الصف الاول الثانوى', 'محتوى منهج الصف الاول الثانوى', 4, 0, 0, 0, 0),
(10, ' الصف الثانى الثانوى علمى', ' محتوي الصف الثانى الثانوى علمى', 5, 0, 0, 0, 0),
(11, 'الصف الثالث الثانوى', 'محتوي الصف الثالث الثانوى ', 7, 0, 0, 0, 0),
(12, 'الجبر والاحصاء', ' مجموعة الدروس الخاصة بجزء الجبر والاحصاء الفصل الدراسى الاول', 0, 6, 0, 0, 0),
(13, 'الهندسه والقياس ', 'مجموعه دروس الخاصه بجزء الهندسه والقياس الفصل الدراسى الاول ', 0, 6, 0, 0, 0),
(14, 'الجبر و الاحصاء ', 'مجموعه دروس الخاصه بجزء الجبر والاحصاء  الفصل الدراسى الاول ', 0, 7, 0, 0, 0),
(15, 'الهندسة والقياس', 'مجموعه الدروس الخاصه بجزء الهندسه و القياس الفصل الدراسى الاول ', 0, 7, 0, 0, 0),
(17, 'الجبر والاحصاء', 'مجموعه دروس الخاصه بجزء الجبر والاحصاء  الفصل الدراسى الاول ', 0, 8, 0, 0, 0),
(18, ' حساب المثلثات والهندسه', 'مجموعه الدروس الخاصه بجزء  حساب المثلثات والهندسه الفصل الدراسى الاول ', 0, 8, 0, 0, 0),
(19, 'الجبر وحساب المثلثات ', 'مجموعه دروس الخاصه بجزء الجبر وحساب المثلثات الفصل الدراسى الاول ', 0, 9, 0, 0, 0),
(20, 'الصف الثانى الثانوى ادبى', ' محتوي الصف الثانى الثانوى ادبى', 6, 0, 0, 0, 0),
(21, 'الهندسه التحليليه ', 'مجموعه الدروس الخاصه بجزء الهندسه التحليليه الفصل الدراسى الاول ', 0, 9, 0, 0, 0),
(22, 'الجبر ', 'مجموعه دروس الخاصه بجزء الجبر الفصل الدراسى الاول ', 0, 10, 0, 0, 0),
(23, 'التفاضل', 'مجموعه دروس الخاصه بجزء التفاضل الفصل الدراسى الاول ', 0, 10, 0, 0, 0),
(24, 'حساب المثلثات', 'مجموعه دروس الخاصه بجزء حساب المثلثات  الفصل الدراسى الاول ', 0, 10, 0, 0, 0),
(25, 'الاستاتيكا', 'مجموعه دروس الخاصه بجزء الاستاتيكا  الفصل الدراسى الاول ', 0, 10, 0, 0, 0),
(26, 'الهندسه و القياس ', 'مجموعه الدروس الخاصه بجزء الهندسه و القياس الفصل الدراسى الاول ', 0, 10, 0, 0, 0),
(27, 'الجبر', 'مجموعه الدروس الخاصه بجزء الجبر الفصل الدراسي الاول ', 0, 20, 0, 0, 0),
(28, 'التفاضل', 'مجموعه دروس الخاصه بجزء التفاضل الفصل الدراسى الاول ', 0, 20, 0, 0, 0),
(29, 'حساب مثلثات ', 'مجموعه دروس الخاصه بجزء حساب مثلثات الفصل الدراسى الاول ', 0, 20, 0, 0, 0),
(30, 'التفاضل و التكامل ', 'مجموعه دروس الخاصه بجزء التفاضل والتكامل  ', 0, 11, 0, 0, 0),
(31, 'الجبر والهندسه الفرغيه ', 'مجموعه دروس الخاصه بجزء الجبر والهندسه الفرغيه   الفصل الدراسى الاول ', 0, 11, 0, 0, 0),
(32, 'الاستاتيكا', 'مجموعه دروس الخاصه بجزء الاستاتيك الفصل الدراسى الاول ', 0, 11, 0, 0, 0),
(33, 'الديناميكا', 'مجموعه دروس الخاصه بجزء الديناميكاالفصل الدراسى الاول ', 0, 11, 0, 0, 0),
(34, 'الاحصاء', 'مجموعه دروس الخاصه بجزء الاحصاءالفصل الدراسى الاول ', 0, 11, 0, 0, 0),
(38, 'الاحصاء', ' الاحصاء للصف الثالث الثانوي ', 8, 0, 0, 0, 0),
(39, 'مراجعة على ما لم يتم تدريسة العام الماضي', 'مراجعة على ما لم يتم تدريسة العام الماضي', 0, 7, 0, 0, 0),
(40, 'مراجعة على ما لم يتم تدريسة العام الماضي', 'مراجعة على ما لم يتم تدريسة العام الماضي', 0, 8, 0, 0, 0),
(41, 'مراجعة على ما لم يتم تدريسة العام الماضي', 'مراجعة على ما لم يتم تدريسة العام الماضي', 0, 9, 0, 0, 0),
(42, 'مراجعة على ما لم يتم تدريسة العام الماضي', 'مراجعة على ما لم يتم تدريسة العام الماضي', 0, 10, 0, 0, 0),
(43, 'مراجعة على ما لم يتم تدريسة العام الماضي', 'مراجعة على ما لم يتم تدريسة العام الماضي', 0, 20, 0, 0, 0),
(44, 'مراجعة على ما لم يتم تدريسة العام الماضي', 'مراجعة على ما لم يتم تدريسة العام الماضي', 0, 11, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `comment_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `comment_data` date NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 0,
  `member_id` int(11) NOT NULL,
  `post_id` int(11) DEFAULT NULL,
  `lesson_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`comment_id`, `comment`, `comment_data`, `status`, `member_id`, `post_id`, `lesson_id`) VALUES
(10, 'نسئل الله التوفيق', '2020-07-17', 1, 23, 14, NULL),
(15, 'لا يوجد امتحان على ذلك الدرس', '2020-08-08', 1, 1, NULL, 24),
(17, 'لا يوجد امتحان على ذلك الدرس', '2020-08-08', 1, 1, NULL, 25),
(19, 'لا يوجد امتحان على ذلك الدرس', '2020-08-08', 1, 1, NULL, 26),
(21, 'لا يوجد امتحان على ذلك الدرس', '2020-08-08', 1, 1, NULL, 27),
(23, 'لا يوجد امتحان على ذلك الدرس', '2020-08-08', 1, 1, NULL, 28),
(24, 'لا يوجد امتحان على ذلك الدرس	', '2020-08-10', 1, 1, NULL, 5),
(25, 'لا يوجد امتحان على ذلك الدرس', '2020-08-10', 1, 1, NULL, 6),
(26, 'لا يوجد امتحان على ذلك الدرس', '2020-08-10', 1, 1, NULL, 9),
(27, 'لا يوجد امتحان على ذلك الدرس', '2020-08-10', 1, 1, NULL, 8),
(28, 'لا يوجد امتحان على ذلك الدرس', '2020-08-10', 1, 1, NULL, 11),
(29, 'لا يوجد امتحان على ذلك الدرس', '2020-08-10', 1, 1, NULL, 10);

-- --------------------------------------------------------

--
-- Table structure for table `exams`
--

CREATE TABLE `exams` (
  `exam_id` int(11) NOT NULL,
  `exam_name` varchar(255) NOT NULL,
  `exam_date` date NOT NULL,
  `categ_id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `lesson_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `lessons`
--

CREATE TABLE `lessons` (
  `lesson_id` int(11) NOT NULL,
  `lesson_name` varchar(255) NOT NULL,
  `lesson_description` text NOT NULL,
  `video` varchar(255) NOT NULL,
  `lesson_data` date NOT NULL,
  `member_id` int(11) NOT NULL,
  `cat_id` int(11) NOT NULL,
  `tags` varchar(225) NOT NULL,
  `allow_comments` tinyint(4) NOT NULL DEFAULT 0,
  `Approve` tinyint(1) NOT NULL DEFAULT 0,
  `pdf` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `lessons`
--

INSERT INTO `lessons` (`lesson_id`, `lesson_name`, `lesson_description`, `video`, `lesson_data`, `member_id`, `cat_id`, `tags`, `allow_comments`, `Approve`, `pdf`) VALUES
(5, 'مراجعة على ماسبق دراسته في المرحلة الإبتدائية', 'مراجعة على ماسبق دراسته في المرحلة الإبتدائية', '<iframe width=\"560\" height=\"315\" src=\"https://www.youtube.com/embed/XBEErbSfpdc\" frameborder=\"0\" allow=\"accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture\" allowfullscreen></iframe>', '2020-08-06', 1, 12, 'مراجعة,', 0, 1, 'https://drive.google.com/file/d/1FtdNqLXVQjQQpyPQN4GWGmP6l74jHbzJ/view?usp=drivesdk'),
(6, 'مجموعة الاعداد النسبية', 'مجموعة الاعداد النسبية', '<iframe width=\"560\" height=\"315\" src=\"https://www.youtube-nocookie.com/embed/aviJk_Tn1sU\" frameborder=\"0\" allow=\"accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture\" allowfullscreen></iframe>', '2020-07-13', 1, 12, 'الاعدادالنسبية', 0, 1, 'https://drive.google.com/file/d/1WttoeAeXywFsT15xgAF157G5uWfd-Xgc/view'),
(8, 'الجذر التعكيبى للعد النسبى ن ', 'الجذر التعكيبى للعد النسبى ن ', '<iframe width=\"560\" height=\"315\" src=\"https://www.youtube-nocookie.com/embed/WojZNyjC-XM\" frameborder=\"0\" allow=\"accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture\" allowfullscreen></iframe>', '2020-07-13', 1, 14, 'الجذر ,  الجذرالتكعيبى, العددالنسبى, معادلةالدرجةالثالثة,', 0, 1, 'https://drive.google.com/file/d/1U3-CDZMbPnrGJtdTOPOoXcOv20NoOZMQ/view'),
(9, 'متوسطات المثلث ', 'متوسطات المثلث ', '<iframe width=\"560\" height=\"315\" src=\"https://www.youtube-nocookie.com/embed/sSMFMrBkWQk\" frameborder=\"0\" allow=\"accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture\" allowfullscreen></iframe>', '2020-07-13', 1, 15, 'متوسطات-المثلث, نقطةتقاطع-المتوسطات,', 0, 1, 'https://drive.google.com/file/d/1SpCDN7onvc4kiVVA5BIq2q9CitMDjy9v/view'),
(10, 'حاصل الضرب الديكارتى', 'حاصل الضرب الديكارتى', 'حاصل الضرب الديكارتى', '2020-07-13', 1, 17, 'الضرب-الديكارتى', 0, 1, 'https://drive.google.com/file/d/1Y4Z_iHis6EqBI09pAvYDCXYGYFK4JHFU/view'),
(11, 'حساب المثلثات', 'النسب المثلثية للزاوية الحادة - النسب المثلثية الاساسية لبعض الزوايا الخاصة 30-45-60', 'حساب المثلثات', '2020-07-13', 1, 18, 'حساب-المثلثات, الزاويةالحادة, الزواياالخاصة', 0, 1, 'https://drive.google.com/file/d/1YOMgxUfjYGLcn1B8Kkl4P3A7ip_j4e7h/view'),
(13, 'ايجاد نهاية الدالة بيانيا', 'مقدمة في النهايات', 'النهايات', '2020-07-13', 1, 23, '', 0, 0, ''),
(14, 'حساب المثلثات', 'قاعدة الجيب', 'حساب المثلثات', '2020-07-13', 1, 24, '', 0, 0, NULL),
(15, 'القوى', 'محصلة قوتين', 'القوى', '2020-07-13', 1, 25, '', 0, 0, NULL),
(16, 'المستقيمات والمستويات في الفراغ', 'المستقيمات والمستويات في الفراغ', 'المستقيمات والمستويات في الفراغ', '2020-07-13', 1, 26, '', 0, 0, NULL),
(17, 'الدوال الحقيقة', 'الدوال الحقيقة', 'الدوال الحقيقة', '2020-07-13', 1, 27, '', 0, 0, NULL),
(18, 'ايجاد نهاية الدالة بيانيا', '  مقدمة في النهايات وايجاد نهاية الدالة بيانيا', '<iframe width=\"560\" height=\"315\" src=\"https://www.youtube-nocookie.com/embed/SWzgv-Qu9mY\" frameborder=\"0\" allow=\"accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture\" allowfullscreen></iframe>', '2020-07-13', 1, 28, 'تفاضل, نهايات, بيانيا, جبريا,', 0, 1, 'https://drive.google.com/file/d/1RALVaXj89itrVctTzMB5LHpPu-QYC4Aa/view'),
(19, 'قاعدة الجيب', 'قاعدة الجيب', 'قاعدة الجيب', '2020-07-13', 1, 29, '', 0, 0, NULL),
(20, 'الباب الأول', 'الارتباط والانحدار', '<iframe width=\"560\" height=\"315\" src=\"https://www.youtube-nocookie.com/embed/VicbiATnhTE\" frameborder=\"0\" allow=\"accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture\" allowfullscreen></iframe>', '2020-08-06', 1, 35, 'قانون سبيرمان, قانون بيرسون , معادلة خط الأنحدار', 0, 0, NULL),
(21, 'إشتقاق الدوال المثلثية', 'اشتقاق الدوال المثلثية واستخدام قوانين الاشتقاق ', '<iframe width=\"560\" height=\"315\" src=\"https://www.youtube-nocookie.com/embed/6fuqEoU9Y3k\" frameborder=\"0\" allow=\"accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture\" allowfullscreen></iframe>', '2020-08-06', 1, 30, 'اشتقاق الدوال المثلثية , قواعد الأشتقاق', 0, 0, NULL),
(22, 'حل معادلة الدرجة الثانية في متغير واحد', 'حل معادلة الدرجة الثانية في متغير واحد جبريا وبيانيا  ', '<iframe width=\"560\" height=\"315\" src=\"https://www.youtube-nocookie.com/embed/5ozWnQm0VOM\" frameborder=\"0\" allow=\"accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture\" allowfullscreen></iframe>', '2020-08-06', 1, 19, 'معدلة, بيانيا, جبريا, القانون العام', 0, 1, NULL),
(24, 'حل المعادلات من الدرجة الأولى في مجهول واحد في ن', 'حل المعادلات من الدرجة الأولى في مجهول واحد في ن', '<iframe width=\"560\" height=\"315\" src=\"https://www.youtube-nocookie.com/embed/BAIxUh1CS9A\" frameborder=\"0\" allow=\"accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture\" allowfullscreen></iframe>', '2020-08-08', 1, 39, 'المعادلات, الدرجةالاولى, مجهول-واحد, ن', 0, 1, 'https://drive.google.com/file/d/1A2MYpUTvMLuigoHkcD6buU1L3CCOCt6j/view'),
(25, 'أستخدم المعادلات لحل المسائل اللفظية', 'أستخدم المعادلات لحل المسائل اللفظية', '<iframe width=\"560\" height=\"315\" src=\"https://www.youtube.com/embed/wvblVz9_3m8\" frameborder=\"0\" allow=\"accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture\" allowfullscreen></iframe>', '2020-08-08', 1, 39, 'المعادلات,المسائل-اللفظية, ن', 0, 1, 'https://drive.google.com/file/d/1AGeqGm4TeDxvu7TTF2YNk-7IcWE8c8pR/view'),
(26, 'حل المتباينات في ن', 'حل المتباينات في ن', '<iframe width=\"560\" height=\"315\" src=\"https://www.youtube.com/embed/wydYDnWWGbg\" frameborder=\"0\" allow=\"accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture\" allowfullscreen></iframe>', '2020-08-08', 1, 39, 'المتباينات, ن', 0, 1, 'https://drive.google.com/file/d/1AYgiVk24X0CXEgaV82T2CF8SyDA1iCK8/view'),
(27, 'نظرية فيثاغورث', 'نظرية فيثاغورث', '<iframe width=\"560\" height=\"315\" src=\"https://www.youtube.com/embed/xiUohFscSCM\" frameborder=\"0\" allow=\"accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture\" allowfullscreen></iframe>', '2020-08-08', 1, 39, 'نظرية ,فيثاغورث', 0, 1, 'https://drive.google.com/file/d/1B8_3NH0zot9vfO31aYx9F78KFie8vYQ2/view'),
(28, 'التشابة', 'التشابة', '<iframe width=\"560\" height=\"315\" src=\"https://www.youtube.com/embed/uJgMZq1GvgU\" frameborder=\"0\" allow=\"accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture\" allowfullscreen></iframe>', '2020-08-08', 1, 40, 'التشابة', 0, 1, 'https://drive.google.com/file/d/1BWv4hWhMiz6RrygBHZYUhAFmC6O3XXGk/view');

-- --------------------------------------------------------

--
-- Table structure for table `members`
--

CREATE TABLE `members` (
  `userid` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `fullname` varchar(255) DEFAULT NULL,
  `groupid` int(11) NOT NULL DEFAULT 0,
  `regstatus` int(11) NOT NULL DEFAULT 0,
  `date` date NOT NULL,
  `avatar` varchar(225) NOT NULL DEFAULT 'img.png',
  `lil` text DEFAULT 'ادخل المهمة الاولى',
  `exam answer` int(11) DEFAULT NULL,
  `lil_data` date NOT NULL DEFAULT current_timestamp(),
  `phone` int(20) DEFAULT NULL,
  `only` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `members`
--

INSERT INTO `members` (`userid`, `username`, `password`, `email`, `fullname`, `groupid`, `regstatus`, `date`, `avatar`, `lil`, `exam answer`, `lil_data`, `phone`, `only`) VALUES
(1, 'ahmed', '601f1889667efaebb33b8c12572835da3f027f78', 'ahmed@gmail.com', 'احمد الطارون', 4, 1, '2020-07-15', '556335_image1.jpg', 'يجب الدراسة', 0, '2020-07-22', 0, 0),
(23, 'mohamed abdelaziz', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'ahmed1@gmail.com', 'محمد عبد العزيز عبد العال', 0, 1, '2020-07-17', 'img.png', 'تجهيز الدروس', 0, '2020-07-17', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `post`
--

CREATE TABLE `post` (
  `post_id` int(11) NOT NULL,
  `post_name` varchar(255) NOT NULL,
  `post_description` text NOT NULL,
  `post_image` varchar(255) NOT NULL,
  `post_data` date NOT NULL,
  `allow_comment` tinyint(4) NOT NULL DEFAULT 0,
  `users` int(11) NOT NULL,
  `tags` varchar(225) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `post`
--

INSERT INTO `post` (`post_id`, `post_name`, `post_description`, `post_image`, `post_data`, `allow_comment`, `users`, `tags`) VALUES
(14, 'بداية العام الدراسى الجديد', 'التعليم 17 اكتوبر بداية العام الدراسى الجديد \r\nويوم 6 فبراير اجازة نصف العام', 'images (72).jpeg', '2020-07-16', 1, 1, 'اجازة, السنة الدراسية, التعليم');

-- --------------------------------------------------------

--
-- Table structure for table `question`
--

CREATE TABLE `question` (
  `id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `ques` text NOT NULL,
  `added_date` date NOT NULL,
  `answer_1` text NOT NULL,
  `answer_2` text NOT NULL,
  `answer_3` text NOT NULL,
  `answer_4` text NOT NULL,
  `right_answer` varchar(255) NOT NULL,
  `photo` varchar(225) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user_token`
--

CREATE TABLE `user_token` (
  `id` int(11) NOT NULL,
  `username` varchar(225) CHARACTER SET utf8 NOT NULL,
  `token` varchar(225) CHARACTER SET utf8 NOT NULL,
  `timemodified` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `user_token`
--

INSERT INTO `user_token` (`id`, `username`, `token`, `timemodified`) VALUES
(1, 'ahmed-goma', 'QLjaU2nVLD', '2020-08-08 12:34:33'),
(2, 'mohamed2020', 'kwb9Ww9Wkb', '2020-08-08 20:37:11');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `answer`
--
ALTER TABLE `answer`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`comment_id`),
  ADD KEY `comments` (`member_id`),
  ADD KEY `com` (`lesson_id`),
  ADD KEY `memb` (`post_id`);

--
-- Indexes for table `exams`
--
ALTER TABLE `exams`
  ADD PRIMARY KEY (`exam_id`);

--
-- Indexes for table `lessons`
--
ALTER TABLE `lessons`
  ADD PRIMARY KEY (`lesson_id`);

--
-- Indexes for table `members`
--
ALTER TABLE `members`
  ADD PRIMARY KEY (`userid`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `post`
--
ALTER TABLE `post`
  ADD PRIMARY KEY (`post_id`),
  ADD KEY `user_name` (`users`);

--
-- Indexes for table `question`
--
ALTER TABLE `question`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ques` (`question_id`);

--
-- Indexes for table `user_token`
--
ALTER TABLE `user_token`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `answer`
--
ALTER TABLE `answer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `exams`
--
ALTER TABLE `exams`
  MODIFY `exam_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `lessons`
--
ALTER TABLE `lessons`
  MODIFY `lesson_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `members`
--
ALTER TABLE `members`
  MODIFY `userid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `post`
--
ALTER TABLE `post`
  MODIFY `post_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `question`
--
ALTER TABLE `question`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_token`
--
ALTER TABLE `user_token`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `com` FOREIGN KEY (`lesson_id`) REFERENCES `lessons` (`lesson_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `comments` FOREIGN KEY (`member_id`) REFERENCES `members` (`userid`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `memb` FOREIGN KEY (`post_id`) REFERENCES `post` (`post_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `post`
--
ALTER TABLE `post`
  ADD CONSTRAINT `user_name` FOREIGN KEY (`users`) REFERENCES `members` (`userid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `question`
--
ALTER TABLE `question`
  ADD CONSTRAINT `ques` FOREIGN KEY (`question_id`) REFERENCES `exams` (`exam_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
