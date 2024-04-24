var hash = db_hash;
fetchJobs(hash);
// alert('kkkkkkkk');
async function fetchJobs(hash) {
  try {
    document.getElementById("error").innerText = "";
    // let baseUrl = 'https://powrserver2.com/api/jobs/' + hash;
    let baseUrl = "http://127.0.0.1:8000/api/jobs/" + hash;

    const response = await fetch(baseUrl);
    const jobs = await response.json();

    if (!response.ok) {
      throw new Error(jobs.message);
    }

    document.getElementById("job-posting").innerHTML = jobs.data.jobs;
    // console.log(jobs.data.jobs);
    document.getElementById("job-filters").innerHTML = jobs.data.filters;
    // console.log(jobs.data.filters);
    const imageName = jobs.data.img;
    // const baseUrl1 = 'https://powrserver2.com/upload/';
    const baseUrl1 = "http://127.0.0.1:8000/upload/";
    const defaultImage = "assets/images/bg.png";

    if (imageName) {
      const imageUrl = baseUrl1 + imageName;
      document.getElementById("bg_img").src = imageUrl;
    } else {
      document.getElementById("bg_img").src = defaultImage;
    }

    applyFilters(hash);
  } catch (error) {
    document.getElementById("error").innerText = error;
  }
}

async function filterJobs(hash, query = "") {
  try {
    document.getElementById("error").innerText = "";
    // let baseUrl = 'https://powrserver2.com/api/jobs/' + hash + query;
    let baseUrl = "http://127.0.0.1:8000/api/jobs/" + hash + query;

    const response = await fetch(baseUrl);
    const jobs = await response.json();

    if (!response.ok) {
      document.getElementById("job-posting").innerHTML = "";
      throw new Error(jobs.message);
    }
    document.getElementById("job-posting").innerHTML = jobs.data.jobs;
  } catch (error) {
    document.getElementById("error").innerText = error;
  }
}

// async function filterJobs(hash, query = "") {
//   try {
//     document.getElementById("error").innerText = "";
//     // let baseUrl = 'https://powrserver2.com/api/jobs/' + hash + query;
//     let baseUrl = "http://127.0.0.1:8000/api/job/" + hash + query;

//     const response = await fetch(baseUrl);
//     const jobs = await response.json();

//     if (!response.ok) {
//       document.getElementById("job-posting").innerHTML = "";
//       throw new Error(jobs.message);
//     }
//     document.getElementById("job-posting").innerHTML = jobs.data.jobs;
//   } catch (error) {
//     document.getElementById("error").innerText = error;
//   }
// }

function applyFilters(hash = "") {
  let categoryFilterObj = document.getElementById("category-filter");
  let locationFilterObj = document.getElementById("location-filter");
  let searchFilterObj = document.getElementById("search-filter");
  let sortingFilterObj = document.getElementById("sorting-filter");

  categoryFilterObj.addEventListener("change", function () {
    let selectedCategory = this.value;
    let selectedLocation = locationFilterObj.value;
    let searchTerm = searchFilterObj.value;
    let selectedSort = sortingFilterObj.value;
    filterJobs(
      hash,
      `?category=${selectedCategory}&location=${selectedLocation}&search_term=${searchTerm}&sort=${selectedSort}`
    );
  });

  locationFilterObj.addEventListener("change", function () {
    let selectedLocation = this.value;
    let selectedCategory = categoryFilterObj.value;
    let searchTerm = searchFilterObj.value;
    let selectedSort = sortingFilterObj.value;
    filterJobs(
      hash,
      `?category=${selectedCategory}&location=${selectedLocation}&search_term=${searchTerm}&sort=${selectedSort}`
    );
  });

  searchFilterObj.addEventListener("input", function () {
    let searchTerm = this.value;
    let selectedCategory = categoryFilterObj.value;
    let selectedLocation = locationFilterObj.value;
    let selectedSort = sortingFilterObj.value;
    filterJobs(
      hash,
      `?category=${selectedCategory}&location=${selectedLocation}&search_term=${searchTerm}&sort=${selectedSort}`
    );
  });

  document
    .getElementById("sorting-filter")
    .addEventListener("input", function () {
      let selectedSort = this.value;
      let selectedCategory = categoryFilterObj.value;
      let selectedLocation = locationFilterObj.value;
      let searchTerm = searchFilterObj.value;
      filterJobs(
        hash,
        `?category=${selectedCategory}&location=${selectedLocation}&search_term=${searchTerm}&sort=${selectedSort}`
      );
    });
}

// $(document).ready(function() {
//     $(document).on('click', '.job_details', function(e) {
//         e.preventDefault();
//         $('#job-filters,#job-posting').hide();
//         $('#details-sectn').show();

//         let job_id = $(this).attr('data-id');
//         let hash = $(this).attr('data-hash');

//         // let apiUrl = 'https://powrserver2.com/api/jobs/' + hash + '/' + job_id;
//         let apiUrl = 'http://127.0.0.1:8000/api/jobs/' + hash + '/' + job_id;
//        $.ajax({
//          type: 'GET',
//            url: apiUrl,
//            dataType: 'json',
//            success: function(response) {
//                if (response.status == 200) {
//                 // console.log(response.html);
//                 $('#details-sectn').html(response.html);
//                }
//                else {
//                 console.log('Error:', response.message);
//             }
//            },
//            error: function(error) {
//                console.log(error);
//            }
//        });

//     });
// //html for main_content to apend
//     var main_html= `  <!-- Hero section -->
//         <section id="hero-section" class="my-3">
//             <nav class="navbar navbar-expand-md navbar-dark" style="background-color:#849c3d">
//                 <div class="container-fluid">
//                     <a class="navbar-brand" href="javascript:void(0)">
//                         <img src="" id="bg_img">
//                     </a>
//                     <div class="collapse navbar-collapse">
//                         <ul class="navbar-nav mx-auto">
//                             <li class="nav-item">
//                                 <a class="nav-link active" href="javascript:void(0)">Find jobs by category, location or title.</a>
//                             </li>
//                         </ul>
//                     </div>
//                 </div>
//             </nav>
//         </section>
//         <main id="jobs-section">
//             <!-- Filters section -->
//             <section id="job-filters" class="my-3">

//             </section>
//             <div class="text-danger text-center" id="error"></div>
//             <!-- Jobs section -->
//             <section class="my-3" id="job-posting">
//                 <!-- All jobs will be injected here -->
//             </section>
//             <section id="details-sectn" class="my-3 details-section  container  p-5 mb-2" >

//             </section>

//         </main>`;
//          $('#main_html1').html(main_html);

// })

$(document).ready(function () {
  $(document).on("click", ".job_details", function (e) {
    e.preventDefault();
    // $('#job-filters,#job-posting').hide();
    // $('#details-sectn').show();

    // Extracting hash and job_id from the href attribute of the clicked link
    let href = $(this).attr("href");
    let params = href.split("/");
    let hash = params[params.length - 2];
    let job_id = params[params.length - 1];

    // let apiUrl = 'https://powrserver2.com/api/jobs/' + hash + '/' + job_id;
    let apiUrl = "http://127.0.0.1:8000/api/jobs/" + hash + "/" + job_id;
    $.ajax({
      type: "GET",
      url: apiUrl,
      dataType: "json",
      success: function (response) {
        if (response.status == 200) {
          // console.log(response.html);
          $("#details-sectn").html(response.html);
        } else {
          console.log("Error:", response.message);
        }
      },
      error: function (error) {
        console.log(error);
      },
    });
  });

   // Function to handle navbar color selection
 
  //html for main_content to apend
  var main_html = `  <!-- Hero section -->
            <section id="hero-section" class="my-3">
                <nav class="navbar navbar-expand-md navbar-dark" style="background-color:#849c3d">
                    <div class="container-fluid">
                        <a class="navbar-brand" href="javascript:void(0)">
                            <img src="" id="bg_img">
                        </a>
                        <div class="collapse navbar-collapse">
                            <ul class="navbar-nav mx-auto">
                                <li class="nav-item">
                                    <a class="nav-link active" href="javascript:void(0)">Find jobs by category, location or title.</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>
            </section> 
            <main id="jobs-section">
                <!-- Filters section -->
                <section id="job-filters" class="my-3">
        
                </section>
                <div class="text-danger text-center" id="error"></div>
                <!-- Jobs section -->
                <section class="my-3" id="job-posting">
                    <!-- All jobs will be injected here -->
                </section>
                <section id="details-sectn" class="my-3 details-section  container  p-5 mb-2" >
        
                </section>
        
            </main>`;
  $("#main_html1").html(main_html);
});




