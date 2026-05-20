<?php
session_start();
require_once("settings.php");
?>
<?php include "header.inc" ?>

      <main>
         <!-- group name and class time -->
         <section id="groupInfo" class="contentBox" aria-label="Group Information">
            <h2>Group Information</h2>
            <ul>
               <li>
                  Group Details
                  <ul>
                     <li>Group Name: <strong>Goobers</strong></li>
                     <li>Class Day and Time: <strong>Wednesday 2:30</strong></li>
                  </ul>
               </li>
            </ul>
         </section>
         <!-- member contributions displayed using definition lists -->
         <section id="contributions" class="contentBox" aria-label="Team Contributions">
            <h2>Introducing - the team.</h2>
            <dl>
               <dt>Aiden</dt>
               <dd class="student-id">Student ID: 106501062</dd>
               <dd>Worked on the About Us page.</dd>
               <dd>
                  “Pourquoi les programmeurs préfèrent-ils le mode sombre? Parce que la lumière attire les bugs.”
                  (“Why do programmers prefer dark mode? Because light attracts bugs.”)
               </dd>
               <dt>Tyler</dt>
               <dd class="student-id">Student ID: 106524221</dd>
               <dd>Worked on the Apply page.</dd>
               <dd>
                  "Hay 10 tipos de personas en el mundo: las que entienden el binario y las que no."
                  (There are 10 types of people in the world: those who understand binary, and those who don’t.)
               </dd>
               <dt>Huw</dt>
               <dd class="student-id">Student ID: 106515526</dd>
               <dd>Worked on the Jobs page.</dd>
               <dd>
                  "SQLクエリがバーに入って2つのテーブルに近づき、『一緒に結合できますか？』と尋ねた"
                  (A SQL query goes into a bar, walks up to two tables and asks, ‘Can I join you?’)
               </dd>
               <dt>Ned</dt>
               <dd class="student-id">Student ID: 102566889</dd>
               <dd>Worked on the Information page.</dd>
               <dd>
                  "Um zu verstehen, was Rekursion ist, musst du zuerst Rekursion verstehen."
                  (To understand what recursion is, you must first understand recursion.)
               </dd>
            </dl>
         </section>
         <!-- group photo -->
         <section id="groupPhoto" class="contentBox" aria-label="Group Photo">
            <h2>Group Photo</h2>
            <figure>
               <!-- Image generated using GPT Image 1.5 (OpenAI, October 2025). Reviewed and edited before use. -->
               <img src="images/working.webp" alt="Group photo of the Goobers team">
               <figcaption style="color:#8b6b13">
                  <strong>Our group working together on the project.</strong>
               </figcaption>
            </figure>
         </section>
         <!-- fun facts -->
         <section id="funFacts" class="contentBox" aria-label="Fun Facts">
            <h2>Fun Facts</h2>
            <table>
               <caption>Group Fun Facts</caption>
               <tr>
                  <th>Name</th>
                  <th>Hobby</th>
                  <th>Favourite Coding Language</th>
               </tr>
               <tr>
                  <td>Aiden</td>
                  <td>Playing games</td>
                  <td>Python</td>
               </tr>
               <tr>
                  <td>Tyler</td>
                  <td>Playing Siege</td>
                  <td>Javascript</td>
               </tr>
               <tr>
                  <td>Ned</td>
                  <td>Working on websites</td>
                  <td>Assembly</td>
               </tr>
               <tr>
                  <td>Huw</td>
                  <td>Working on the CSS of websites</td>
                  <td>CSS</td>
               </tr>
            </table>
         </section>
      </main>

<?php include "footer.inc" ?>
