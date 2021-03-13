-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 21 أغسطس 2020 الساعة 19:10
-- إصدار الخادم: 10.1.29-MariaDB
-- PHP Version: 7.1.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `laquatat`
--

-- --------------------------------------------------------

--
-- بنية الجدول `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- إرجاع أو استيراد بيانات الجدول `categories`
--

INSERT INTO `categories` (`id`, `name`, `created_at`) VALUES
(1, 'فوتوشوب', '2020-08-11 11:35:36'),
(2, 'إليستريتور', '2020-08-11 11:35:36'),
(3, 'تصميم المواقع', '2020-08-11 11:36:04'),
(4, 'صور فوتوغرافية', '2020-08-11 11:36:04'),
(5, 'إنديزاين', '2020-08-11 11:36:24'),
(6, 'تصميم الشعارات', '2020-08-11 11:36:24'),
(7, 'تصميمات السوشيال ميديا', '2020-08-11 11:36:55'),
(8, 'أعمال فنية', '2020-08-11 11:36:55');

-- --------------------------------------------------------

--
-- بنية الجدول `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `picture` text NOT NULL,
  `alt` text NOT NULL,
  `description` text NOT NULL,
  `privacy` varchar(50) NOT NULL,
  `category_id` int(11) NOT NULL,
  `likes` int(11) NOT NULL,
  `views` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- بنية الجدول `post_likes`
--

CREATE TABLE `post_likes` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `liked_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- بنية الجدول `post_views`
--

CREATE TABLE `post_views` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `viewed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- بنية الجدول `profile_likes`
--

CREATE TABLE `profile_likes` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `profile_id` int(11) NOT NULL,
  `liked_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- بنية الجدول `questions`
--

CREATE TABLE `questions` (
  `id` int(11) NOT NULL,
  `question` text NOT NULL,
  `answer` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- إرجاع أو استيراد بيانات الجدول `questions`
--

INSERT INTO `questions` (`id`, `question`, `answer`) VALUES
(1, 'ما هو موقع لقطات ؟', ' لقطات هي منصة عربية تمكّن المستخدم من عرض الإبداعات البصرية، وإنشاء معرض أعمال إحترافي مجانا، تأسست المنصة من أجل دعم المصممين والمطورين العرب، وتمكينهم من الوصول إلى عملائهم المحتملين، وعقد صفقات عمل عبر الإنترنيت فقط. '),
(2, 'كيف تعمل المنصة ؟', ' يمكنك التعامل مع المنصة بسهولة وسلاسة تامة، فقط قم بإنشاء حساب وانضم إلى مجتمعنا لتستفيد من خدمات المنصة، وذلك برفع أول أعمالك ليشاهدها عملاؤك المحتملون، فالواجهة بسيطة، والعمليات التي ستقوم بها غير معقدة. إذا واجهتك أي مشاكل في التعامل مع المنصة، تواصل معنا، سنكون سعداء بمساعدتك. '),
(3, 'متى يمكنني الوصول إلى الدعم ؟', ' يمكنك الوصول إلى الدعم عند امتلاكك لحساب مميز، أو في حال واجهتك مشكلة مستعصية متعلقة بحماية حسابك وخصوصياتك، أو يمكنك التواصل معنا فقط في المشاكل العادية والمتكررة والتي لا تحتاج لتدخل منا ');

-- --------------------------------------------------------

--
-- بنية الجدول `testimonials`
--

CREATE TABLE `testimonials` (
  `id` int(11) NOT NULL,
  `image` text NOT NULL,
  `name` varchar(255) NOT NULL,
  `profession` varchar(255) NOT NULL,
  `content` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- إرجاع أو استيراد بيانات الجدول `testimonials`
--

INSERT INTO `testimonials` (`id`, `image`, `name`, `profession`, `content`) VALUES
(1, 'http://localhost/laquatat/assets/images/testimonials/user_1.jpg', 'إبراهيم الحوب', 'مطور ويب', ' الشباب العربي في حاجة إلى مثل هذه المبادرات من أجل دفعه إلى تفجير إبداعاته وعرضها للناس. لأن ذلك سيجعله يحس بالفخر ويعيد إليه ثقته المهزوزة. '),
(2, 'http://localhost/laquatat/assets/images/testimonials/user_1.jpg', 'محمد حاتم', 'مؤسس شركة Glaxia', ' منصة لقطات مبادرة جيدة تعزز الإبداع، وترينا القدرات الفائقة للشباب العرب، وتزرع فينا الأمل لمستقبل مشرق '),
(3, 'http://localhost/laquatat/assets/images/testimonials/user_1.jpg', 'أحمد خالد', 'خبير التسويق البصري', ' منصة لقطات باعتبارها المبادرة الأولى من نوعها في العالم العربي، تحفة بكل ما تحمله الكلمة من معنى، فهي خير دليل على المواهب العربية في مجال الإبداعات البصرية ');

-- --------------------------------------------------------

--
-- بنية الجدول `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` text NOT NULL,
  `avatar` text NOT NULL,
  `cover` text NOT NULL,
  `bio` text NOT NULL,
  `likes` int(11) NOT NULL,
  `paypal` text NOT NULL,
  `work` text NOT NULL,
  `password` text NOT NULL,
  `sexe` varchar(50) NOT NULL,
  `theme` varchar(50) NOT NULL,
  `rate` int(11) NOT NULL,
  `account_type` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `activation_key` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- بنية الجدول `why_us`
--

CREATE TABLE `why_us` (
  `id` int(11) NOT NULL,
  `icon` varchar(255) NOT NULL,
  `content` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- إرجاع أو استيراد بيانات الجدول `why_us`
--

INSERT INTO `why_us` (`id`, `icon`, `content`) VALUES
(1, 'ri-briefcase-line', ' معرض أعمال إحترافي وجذّاب '),
(2, 'ri-service-line', ' الوصول إلى العملاء المناسبين '),
(3, 'ri-camera-lens-line', ' تنمية المحتوى البصري العربي ');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `post_likes`
--
ALTER TABLE `post_likes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `post_views`
--
ALTER TABLE `post_views`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `profile_likes`
--
ALTER TABLE `profile_likes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `testimonials`
--
ALTER TABLE `testimonials`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `why_us`
--
ALTER TABLE `why_us`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `post_likes`
--
ALTER TABLE `post_likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `post_views`
--
ALTER TABLE `post_views`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `profile_likes`
--
ALTER TABLE `profile_likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `testimonials`
--
ALTER TABLE `testimonials`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `why_us`
--
ALTER TABLE `why_us`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
