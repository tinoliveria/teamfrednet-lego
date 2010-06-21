package org.frednet.nxt.server.main;
import org.frednet.nxt.server.main.Port;


import java.io.IOException;
import javax.servlet.ServletConfig;
import javax.servlet.ServletException;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;


/**
 * Servlet implementation class Comport
 */
public class Comport extends HttpServlet {
	private static final long serialVersionUID = 1L;
    private NxtControl Nxt = null;   
    /**
     * @see HttpServlet#HttpServlet()
     */
    public Comport() {
    	
        super();
        Nxt = new NxtControl();
		try {
			//Auto detect
			for(int i = 1; i < 50; i++){
				System.out.print("Test Connection on port " + "COM" + String.valueOf(i) + "\n");
				if(Nxt.Connect("COM" + String.valueOf(i))){
					boolean result = Nxt.PlayTone((short)1000,(short)1000); 
					if(result){
						System.out.print("Connection on port " + "COM" + String.valueOf(i) + "\n");
						break;
					}
				}
				Nxt = new NxtControl();
				
			}
			
		} catch (Exception e) {
		
			e.printStackTrace();
		}
        // TODO Auto-generated constructor stub
    }

	/**
	 * @see Servlet#init(ServletConfig)
	 */
	public void init(ServletConfig config) throws ServletException {
		
		
	}

	/**
	 * @see HttpServlet#doGet(HttpServletRequest request, HttpServletResponse response)
	 */
	protected void doGet(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
		
		
		
		request.setAttribute("NXT.voltage",Double.toString(Nxt.BatteryVoltage));
		request.setAttribute("server.port",Nxt.communicationInterface.serialPort.getName().toString() );
		request.getRequestDispatcher("/control.jsp").forward(request,response);
	}

	/**
	 * @see HttpServlet#doPost(HttpServletRequest request, HttpServletResponse response)
	 */
	protected void doPost(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
		String cmd=null;
		try{ cmd = request.getParameter("cmd"); }
		catch(NumberFormatException e){}
		if(cmd!=null){
			Nxt.CommandReader(cmd);
		}
		request.setAttribute("NXT.voltage",Double.toString(Nxt.BatteryVoltage));
		request.setAttribute("server.port",Nxt.communicationInterface.serialPort.getName().toString() );
		request.getRequestDispatcher("control.jsp").forward(request,response);
		
	}

}
