@isset ($vuln_cvss['v3']['metrics'])
<h4>Exploitability Metrics</h4>
<div class="cvss-vector">
    <span class="cvss-title">Attack Vector</span>
    <span class="cvss-item first @if($vuln_cvss['v3']['metrics']['AV'] == 'N') bg-info @endif">Network</span>
    <span class="cvss-item @if($vuln_cvss['v3']['metrics']['AV'] == 'A') bg-info @endif">Adjacent Network</span>
    <span class="cvss-item @if($vuln_cvss['v3']['metrics']['AV'] == 'L') bg-info @endif">Local</span>
    <span class="cvss-item last @if($vuln_cvss['v3']['metrics']['AV'] == 'P') bg-info @endif">Physical</span>
</div>
<div class="cvss-vector">
    <span class="cvss-title">Attack Complexity</span>
    <span class="cvss-item first @if($vuln_cvss['v3']['metrics']['AC'] == 'L') bg-danger @endif">Low</span>
    <span class="cvss-item last @if($vuln_cvss['v3']['metrics']['AC'] == 'H') bg-warning @endif">High</span>
</div>
<div class="cvss-vector">
    <span class="cvss-title">Privileges Required</span>
    <span class="cvss-item first @if($vuln_cvss['v3']['metrics']['PR'] == 'N') bg-danger @endif">None</span>
    <span class="cvss-item @if($vuln_cvss['v3']['metrics']['PR'] == 'L') bg-warning @endif">Low</span>
    <span class="cvss-item last @if($vuln_cvss['v3']['metrics']['PR'] == 'H') bg-info @endif">High</span>
</div>
<div class="cvss-vector">
    <span class="cvss-title">User Interaction</span>
    <span class="cvss-item first @if($vuln_cvss['v3']['metrics']['UI'] == 'N') bg-danger @endif">None</span>
    <span class="cvss-item last @if($vuln_cvss['v3']['metrics']['UI'] == 'R') bg-warning @endif">Required</span>
</div>
<div class="cvss-vector last">
    <span class="cvss-title">Scope</span>
    <span class="cvss-item first @if($vuln_cvss['v3']['metrics']['S'] == 'U') bg-info @endif">Unchanged</span>
    <span class="cvss-item last @if($vuln_cvss['v3']['metrics']['S'] == 'C') bg-info @endif">Changed</span>
</div>

<h4>Impact Metrics</h4>
<div class="cvss-vector">
    <span class="cvss-title">Confidentiality Impact </span>
    <span class="cvss-item first @if($vuln_cvss['v3']['metrics']['C'] == 'N') bg-info @endif">None</span>
    <span class="cvss-item @if($vuln_cvss['v3']['metrics']['C'] == 'L') bg-warning @endif">Low</span>
    <span class="cvss-item last @if($vuln_cvss['v3']['metrics']['C'] == 'H') bg-danger @endif">High</span>
</div>
<div class="cvss-vector">
    <span class="cvss-title">Integrity Impact</span>
    <span class="cvss-item first @if($vuln_cvss['v3']['metrics']['I'] == 'N') bg-info @endif">None</span>
    <span class="cvss-item  @if($vuln_cvss['v3']['metrics']['I'] == 'L') bg-warning @endif">Low</span>
    <span class="cvss-item last @if($vuln_cvss['v3']['metrics']['I'] == 'H') bg-danger @endif">High</span>
</div>
<div class="cvss-vector">
    <span class="cvss-title">Availability Impact</span>
    <span class="cvss-item first @if($vuln_cvss['v3']['metrics']['A'] == 'N') bg-info @endif">None</span>
    <span class="cvss-item @if($vuln_cvss['v3']['metrics']['A'] == 'L') bg-warning @endif">Low</span> 
    <span class="cvss-item last @if($vuln_cvss['v3']['metrics']['A'] == 'H') bg-danger @endif">High</span>
</div>
@endisset

<!--
<pre>
{{ print_r($vuln_cvss)}}
</pre>
--> 

@section('css')
<style type="text/css">

.cvss-title {
    font-weight: bold;
    display: block;
}

.cvss-item {
    border: 1px solid #797979;
    padding-right: 5px;
    padding-left: 5px;
    /**display: table-cell;**/
    display: inline-block;
    margin-left: -2px;
    margin-right: -2px;
}

.cvss-item.first {
    -moz-border-radius-topleft: .25rem;
    -moz-border-radius-bottomleft: .25rem;
    -webkit-border-top-left-radius: .25rem;
    -webkit-border-bottom-left-radius: .25rem;
    border-top-left-radius: .45rem;
    border-bottom-left-radius: .45rem;
}

.cvss-item.last {
    -moz-border-radius-topright: .25rem;
    -moz-border-radius-bottomright: .25rem;
    -webkit-border-top-right-radius: .25rem;
    -webkit-border-bottom-right-radius: .25rem;
    border-top-right-radius: .45rem;
    border-bottom-right-radius: .45rem;
}

.cvss-vector {
    padding-bottom: 6px;
}
.cvss-vector.last {
    padding-bottom: 40px;
}
</style>
@stop