<script src="https://cdnjs.cloudflare.com/ajax/libs/jsbarcode/3.12.3/JsBarcode.all.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white">
        <h5 class="mb-0"><i class="fa fa-tags me-2"></i>Print Product Labels</h5>
    </div>
    <div class="card-body">
        <div class="mb-3">
            <input type="text" class="form-control" id="label-product-search" placeholder="Search products...">
        </div>

        <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
            <table class="table table-sm table-hover">
                <thead style="position: sticky; top: 0; background: #fff;">
                    <tr>
                        <th></th>
                        <th>Product</th>
                        <th>Barcode</th>
                        <th>Price</th>
                        <th style="width:100px;">Qty Labels</th>
                    </tr>
                </thead>
                <tbody id="label-product-list">
                    <?php foreach($products as $p):?>
                    <tr class="label-row" data-name="<?=strtolower(esc($p['description']))?>">
                        <td>
                            <input type="checkbox" class="label-checkbox" value="<?=$p['id']?>">
                        </td>
                        <td><?=esc($p['description'])?></td>
                        <td class="text-muted"><?=esc($p['barcode'])?></td>
                        <td>$<?=number_format($p['amount'],2)?></td>
                        <td>
                            <input type="number" class="form-control form-control-sm label-qty" value="1" min="1">
                        </td>
                    </tr>
                    <?php endforeach;?>
                </tbody>
            </table>
        </div>

        <div class="mt-3 d-flex gap-2">
            <button type="button" class="btn btn-dark" onclick="generate_labels('print')">
                <i class="fa fa-print"></i> Print Labels
            </button>
            <button type="button" class="btn btn-outline-dark" onclick="generate_labels('pdf')">
                <i class="fa fa-file-pdf"></i> Download PDF
            </button>
        </div>
    </div>
</div>

<!-- Hidden print area, only visible via @media print -->
<div id="printable-labels" style="display:none;"></div>

<style>
@media print {
    body * { visibility: hidden; }
    #printable-labels, #printable-labels * { visibility: visible; }
    #printable-labels {
        display: block !important;
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
    }
}
.label-sticker {
    width: 40mm;
    height: 20mm;
    border: 1px dashed #ccc;
    display: inline-flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 2mm;
    box-sizing: border-box;
    margin: 1mm;
    overflow: hidden;
    text-align: center;
}
.label-sticker .label-name {
    font-size: 7px;
    font-weight: bold;
    line-height: 1.1;
    max-height: 16px;
    overflow: hidden;
    margin-bottom: 1px;
}
.label-sticker .label-price {
    font-size: 8px;
    font-weight: bold;
    color: #000;
}
.label-sticker svg {
    max-width: 100%;
    height: 22px;
}
</style>

<script>
// Live search filter on the product list
document.getElementById('label-product-search').addEventListener('keyup', function(){
    var term = this.value.toLowerCase();
    document.querySelectorAll('.label-row').forEach(function(row){
        row.style.display = row.dataset.name.indexOf(term) !== -1 ? '' : 'none';
    });
});

function get_selected_labels()
{
    var selections = [];

    document.querySelectorAll('.label-checkbox:checked').forEach(function(cb){
        var row = cb.closest('tr');
        var name = row.children[1].innerText;
        var barcode = row.children[2].innerText;
        var price = row.children[3].innerText.replace('$','');
        var qty = parseInt(row.querySelector('.label-qty').value) || 1;

        for(var i = 0; i < qty; i++)
        {
            selections.push({ name: name, barcode: barcode, price: price });
        }
    });

    return selections;
}

function generate_labels(mode)
{
    var labels = get_selected_labels();

    if(labels.length == 0)
    {
        show_toast("error", "No products selected", "Check at least one product first.");
        return;
    }

    if(mode == 'print')
    {
        render_labels_for_print(labels);
        setTimeout(function(){ window.print(); }, 200);
    }else{
        generate_labels_pdf(labels);
    }
}

function render_labels_for_print(labels)
{
    var container = document.getElementById('printable-labels');
    container.innerHTML = '';

    labels.forEach(function(label, index){
        var div = document.createElement('div');
        div.className = 'label-sticker';

        var nameDiv = document.createElement('div');
        nameDiv.className = 'label-name';
        nameDiv.innerText = label.name;

        var svg = document.createElementNS("http://www.w3.org/2000/svg", "svg");
        svg.id = 'barcode-print-' + index;

        var priceDiv = document.createElement('div');
        priceDiv.className = 'label-price';
        priceDiv.innerText = '$' + parseFloat(label.price).toFixed(2);

        div.appendChild(nameDiv);
        div.appendChild(svg);
        div.appendChild(priceDiv);
        container.appendChild(div);

        JsBarcode(svg, label.barcode, {
            format: "CODE128",
            width: 1,
            height: 25,
            displayValue: false,
            margin: 0
        });
    });

    container.style.display = 'flex';
    container.style.flexWrap = 'wrap';
}

function generate_labels_pdf(labels)
{
    const { jsPDF } = window.jspdf;
    // A4 page, mm units. Label 40x20mm, small gap between.
    var doc = new jsPDF({ unit: 'mm', format: 'a4' });

    var labelW = 40, labelH = 20, gap = 2;
    var marginX = 5, marginY = 5;
    var pageW = 210, pageH = 297;

    var cols = Math.floor((pageW - marginX*2) / (labelW + gap));
    var rows = Math.floor((pageH - marginY*2) / (labelH + gap));
    var perPage = cols * rows;

    labels.forEach(function(label, index){
        var posOnPage = index % perPage;

        if(posOnPage == 0 && index != 0)
        {
            doc.addPage();
        }

        var col = posOnPage % cols;
        var row = Math.floor(posOnPage / cols);

        var x = marginX + col * (labelW + gap);
        var y = marginY + row * (labelH + gap);

        // Render barcode to an offscreen canvas, then embed as image
        var canvas = document.createElement('canvas');
        JsBarcode(canvas, label.barcode, {
            format: "CODE128",
            width: 2,
            height: 60,
            displayValue: false,
            margin: 0
        });
        var barcodeImg = canvas.toDataURL('image/png');

        doc.setDrawColor(200);
        doc.rect(x, y, labelW, labelH);

        doc.setFontSize(6);
        doc.setFont(undefined, 'bold');
        var nameLines = doc.splitTextToSize(label.name, labelW - 4);
        doc.text(nameLines.slice(0,2), x + labelW/2, y + 3, { align: 'center' });

        doc.addImage(barcodeImg, 'PNG', x + 3, y + 6, labelW - 6, 8);

        doc.setFontSize(8);
        doc.text('$' + parseFloat(label.price).toFixed(2), x + labelW/2, y + 18, { align: 'center' });
    });

    doc.save('product_labels_' + Date.now() + '.pdf');
}
</script>