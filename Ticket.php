<?php
class Ticket
{
	private $db;
	private $ticketNumber;
	
	public function __construct()
	{
		$this->db = new mysqli('localhost', 'assignment2', 'password123', 'assignment2');
		if ($this->db->connect_error)
		{
			die ("Could not connect to db: " . $db->connect_error);
		}
		$result = $this->db->query("select Tickets.Ticket from Tickets");
		$entries = $result->num_rows;
		$result->data_seek($entries-1);
		$row = $result->fetch_row();
		$this->ticketNumber = $row[0]+1;
	}
	
	public function addTicket($name, $email, $subject, $body)
	{
		$currentdate = date("M d Y h:iA");
		$query = "insert into Tickets values ('$this->ticketNumber', '$currentdate' ,'$name', '$email', '$subject', '$body')";
		$this->db->query($query) or die ("Invalid insert " . $this->db->error);
		$query = "insert into TicketStatus values ('$this->ticketNumber', '', 'open')";
		$this->db->query($query) or die ("Invalid insert " . $this->db->error);
	}
	
	public function getNumber()
	{
		return $this->ticketNumber;
	}
	
}
?>