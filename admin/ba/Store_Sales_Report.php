<section class="p-3 p-md-4 p-xl-5">
  <div class="container">
    <div class="card border-light-subtle shadow-sm" >
      <div class="row g-0" >
      <div class="col-12">
          <div class="card-body p-3 p-md-4 p-xl-5">
            <div class="row">
              <div class="col-12">
                <div class="mb-5">
                  <h2 class="h3">Store Sales Report</h2>
                  <p class="lead">View statistics about your store's sales performance.</p>
                  <p class="text-muted">Report for: <span id="date-range">January 1, 2024 - May 31, 2024</span></p>
                </div>
                <div class="mb-4">
                  <label for="brand-select" class="form-label">Filter by Brand:</label>
                  <select class="form-select" id="brand-select">
                    <option selected>All Brands</option>
                    <option>Apple</option>
                    <option>Samsung</option>
                    <option>Sony</option>
                    <option>Huawei</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <h3 class="h5">Most Sold Products</h3>
                <div class="table-responsive">
                  <table class="table table-striped">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Product Name</th>
                        <th>Quantity Sold</th>
                      </tr>
                    </thead>
                    <tbody>
                      <!-- Table rows to be dynamically updated based on filter selection, example rows given -->
                      <tr>
                        <td>1</td>
                        <td>Product A (Apple)</td>
                        <td>100</td>
                      </tr>
                      <tr>
                        <td>2</td>
                        <td>Product B (Samsung)</td>
                        <td>85</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <h3 class="h5">Most Profitable Products</h3>
                <div class="table-responsive">
                  <table class="table table-striped">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Product Name</th>
                        <th>Profit Margin</th>
                      </tr>
                    </thead>
                    <tbody>
                      <!-- Table rows to be dynamically updated based on filter selection, example rows given -->
                      <tr>
                        <td>1</td>
                        <td>Product A (Apple)</td>
                        <td>$500</td>
                      </tr>
                      <tr>
                        <td>2</td>
                        <td>Product B (Samsung)</td>
                        <td>$450</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>